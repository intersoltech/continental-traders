<?php

namespace App\Http\Controllers;

use App\Models\SalesItem;
use Illuminate\Http\Request;

class SalesItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesItems = SalesItem::with(['sale', 'product'])->latest()->get();
        return view('sales_items.index', compact('salesItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sales_items.create', compact('sales', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->selling_price;
        $subtotal = $price * $request->quantity;

        SalesItem::create([
            'sale_id' => $request->sale_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $price,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('sales-items.index')->with('success', 'Sales item created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(SalesItem $salesItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesItem $salesItem)
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sales_items.edit', compact('salesItem', 'sales', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesItem $salesItem)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->selling_price;
        $subtotal = $price * $request->quantity;

        $salesItem->update([
            'sale_id' => $request->sale_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $price,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('sales-items.index')->with('success', 'Sales item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesItem $salesItem)
    {
        $salesItem->delete();
        return redirect()->route('sales-items.index')->with('success', 'Sales item deleted successfully.');
    }
}
