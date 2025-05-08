<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['vendor', 'items.product'])->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::all();
        $products = Product::all();
        return view('purchases.create', compact('vendors', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'payment_method' => 'required|in:cash,online,card',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.cost_price' => 'required|numeric|min:0',
        ]);

        

        // Generate invoice number
        $invoiceNo = 'INV-' . now()->format('YmdHis'); // Example: INV-20250508190730

        // Create the purchase entry (without `quantity`)
        $purchase = Purchase::create([
            'vendor_id' => $request->vendor_id,
            'purchase_date' => $request->purchase_date,
            'invoice_no' => $invoiceNo,
            'payment_method' => $request->payment_method,
            'total_amount' => 0, // We'll update the total_amount after adding items
        ]);
        // dd($purchase);

        $totalAmount = 0;

        // Loop through products and create purchase items
        foreach ($request->products as $item) {
            $subtotal = $item['quantity'] * $item['cost_price'];
            $totalAmount += $subtotal;

            // Create purchase items entry
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['cost_price'],
                'subtotal' => $subtotal,
            ]);

            // Update inventory for the product
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $item['product_id']],
                ['quantity' => 0]
            );
            $inventory->increment('quantity', $item['quantity']);
        }

        // After all items are added, update the total amount in the purchase table
        $purchase->update(['total_amount' => $totalAmount]);

        // Redirect with success message
        return redirect()->route('purchases.index')->with('success', 'Purchase recorded and inventory updated.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $vendors = Vendor::all();
        $products = Product::all();
        return view('purchases.edit', compact('purchase', 'vendors', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,online,card',
        ]);

        $purchase->update($request->all());

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
