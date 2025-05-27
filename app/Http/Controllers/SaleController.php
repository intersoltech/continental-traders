<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF; 


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer', 'saleItems.product')->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('inventory')->get();
        return view('sales.create', compact('products'));
    }

    public function show($id)
    {
        $sale = Sale::with('saleItems.product', 'customer')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->products);
        // Step 1: Validate
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'new_customer_phone' => 'required_without:customer_id|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,online,card',
        ]);

        DB::beginTransaction();

        try {
            // Step 3: Create or find customer
            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->new_customer_phone,
                    'address' => $request->new_customer_address,
                ]);
            }

            // Step 4: Check inventory and calculate totals
            $totalAmount = 0;
            $products = $request->input('products', []);
            // dd($products);
            foreach ($products as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])->first();

                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for product ID: {$item['product_id']}");
                }

                $totalAmount += $item['price'] * $item['quantity'];
            }

            $discount = $request->discount ?? 0;
            $finalTotal = $totalAmount - $discount;

            // Step 5: Create sale
            $sale = Sale::create([
                'customer_id' => $customer->id,
                'discount' => $discount,
                'total' => $finalTotal,
                'payment_method' => $request->payment_method,
            ]);

            // Step 6: Create sale items and decrement inventory
            foreach ($products as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);

                // Decrement inventory
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                $inventory->decrement('quantity', $item['quantity']);
            }

            // Step 7: Commit
            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale created successfully.');

        } catch (\Exception $e) {
            // Step 8: Rollback on error
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        $products = Product::with('inventory')->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'customer.name' => 'required|string|max:255',
            'customer.contact' => 'required|string|max:255',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,online,card',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::with('saleItems')->findOrFail($id);

            // Restore inventory from previous sale items
            foreach ($sale->saleItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }

            // Delete old sale items
            $sale->saleItems()->delete();

            // Recreate customer if changed
            $customer = Customer::firstOrCreate(
                ['name' => $request->customer['name']],
                ['contact' => $request->customer['contact']]
            );

            $totalAmount = 0;
            $saleItemsData = [];
            $products = $request->input('products', []);

            // Re-validate inventory and calculate new total
            foreach ($products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $inventory = Inventory::where('product_id', $product->id)->first();

                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $lineTotal = $item['price'] * $item['quantity'];
                $totalAmount += $lineTotal;

                $saleItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $lineTotal,
                ];
            }

            $discount = $request->discount ?? 0;
            $finalTotal = $totalAmount - $discount;

            // Update Sale
            $sale->update([
                'customer_id' => $customer->id,
                'discount' => $discount,
                'total' => $finalTotal,
                'payment_method' => $request->payment_method,
                // 'pdf_path' => 'optional_path_after_pdf_generation'
            ]);

            // Create new sale items and adjust inventory
            foreach ($saleItemsData as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);

                $inventory = Inventory::where('product_id', $itemData['product_id'])->first();
                $inventory->decrement('quantity', $itemData['quantity']);
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Sale update failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with('saleItems')->findOrFail($id);

            // Restore inventory
            foreach ($sale->saleItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }

            $sale->saleItems()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Sale deletion failed: ' . $e->getMessage()]);
        }
    }

    public function receipt(Sale $sale)
    {
        // Eager load customer and sale items with products
        $sale->load('customer', 'saleItems.product');

        return view('sales.receipt', compact('sale'));
    }    

    public function dailyReport($date = null)
    {
        $date = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

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

        return view('sales.daily_report', compact('reportData', 'date'));
    }  

    public function dailyReportPdf($date = null)
    {
        $date = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

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

        $pdf = PDF::loadView('sales.daily_report_pdf', compact('reportData', 'date'));

        return $pdf->download("daily_sales_report_{$date}.pdf");
    }

}
