<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Sale;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function daily()
    {
        $date = Carbon::today();
        // Fetch sales for the given date with related data
        $sales = Sale::with(['customer', 'saleItems.product'])
            ->whereDate('created_at', $date)
            ->get();

        $reportData = [];

        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $item) {
                $customerName = $sale->customer->name ?? 'Unknown';
                $productName = $item->product->name ?? 'Unknown';
                $productType = $item->product->type ?? 'Unknown';

                // Unique key by customer and product or just product as you prefer
                $key = $customerName . '|' . $productName;

                if (!isset($reportData[$key])) {
                    $reportData[$key] = [
                        'customer' => $customerName,
                        'product' => $productName,
                        'type' => $productType,
                        'qty' => 0,
                        'cash' => 0,
                        'online' => 0,
                        'pos' => 0,
                        'amount' => 0,
                        'bill_no' => $sale->id, // adjust if you have bill number field
                    ];
                }

                $lineAmount = $item->price * $item->quantity;

                $reportData[$key]['qty'] += $item->quantity;
                $reportData[$key]['amount'] += $lineAmount;

                // Assign payment method amounts per line item
                if ($sale->payment_method === 'cash') {
                    $reportData[$key]['cash'] += $lineAmount;
                } elseif ($sale->payment_method === 'online') {
                    $reportData[$key]['online'] += $lineAmount;
                } elseif ($sale->payment_method === 'card') {
                    $reportData[$key]['pos'] += $lineAmount;
                }
            }
        }

        return view('reports.daily', compact('reportData', 'date'));        
    }

    public function downloadPDF()
    {
        $date = Carbon::today();
        $sales = Sale::with(['customer', 'saleItems.product'])
            ->whereDate('created_at', $date)
            ->get();

        $reportData = [];

        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $item) {
                $customerName = $sale->customer->name ?? 'Unknown';
                $productName = $item->product->name ?? 'Unknown';

                $key = $customerName . '|' . $productName . '|' . $item->product->type;

                if (!isset($reportData[$key])) {
                    $reportData[$key] = [
                        'customer' => $customerName,
                        'product' => $productName,
                        'qty' => 0,
                        'cash' => 0,
                        'bill_no' => $sale->id,
                        'online' => 0,
                        'pos' => 0,
                        'type' => $item->product->type,
                        'amount' => 0,
                    ];
                }

                $reportData[$key]['qty'] += $item->quantity;
                $reportData[$key]['amount'] += $item->price * $item->quantity;

                if ($sale->payment_method == 'cash') {
                    $reportData[$key]['cash'] += $item->price * $item->quantity;
                } elseif ($sale->payment_method == 'online') {
                    $reportData[$key]['online'] += $item->price * $item->quantity;
                } elseif ($sale->payment_method == 'card') {
                    $reportData[$key]['pos'] += $item->price * $item->quantity;
                }
            }
        }

        $pdf = PDF::loadView('reports.pdf', compact('reportData', 'date'));

        return $pdf->download("daily_sales_report_{$date}.pdf");
    }
}
