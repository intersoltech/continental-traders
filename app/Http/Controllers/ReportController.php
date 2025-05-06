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
        $today = Carbon::today();
        $sales = Sale::whereDate('created_at', $today)->get();

        return view('reports.daily', compact('sales', 'today'));
    }

    public function downloadPDF()
    {
        $today = Carbon::today();
        $sales = Sale::whereDate('created_at', $today)->get();

        $pdf = PDF::loadView('reports.pdf', compact('sales', 'today'));
        return $pdf->download('daily_report_' . $today->format('Y-m-d') . '.pdf');
    }
}
