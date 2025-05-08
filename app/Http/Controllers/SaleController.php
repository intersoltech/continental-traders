<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('product', 'customer')->latest()->get();
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('sales.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);
    
        // Save customer
        $customer = Customer::create([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'address' => $request->customer_address ?? '',
        ]);
    
        // Save sale
        $sale = Sale::create([
            'customer_id' => $customer->id,
            'total_amount' => array_sum(array_column($request->products, 'subtotal')),
        ]);
    
        // Save items
        foreach ($request->products as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }
    
        return redirect()->route('sales.index')->with('success', 'Sale recorded!');
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'discount_percent' => 'nullable|in:0,2,4,6',
            'sale_type' => 'required|in:cash,online,card',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // Price calculation
        $base_price = $product->selling_price * $request->quantity;
        $discount = ($request->discount_percent ?? 0) / 100;
        $total_price = $base_price - ($base_price * $discount);

        // Create sale
        $sale = Sale::create([
            'product_id' => $request->product_id,
            'customer_id' => $request->customer_id,
            'quantity' => $request->quantity,
            'discount_percent' => $request->discount_percent ?? 0,
            'total_price' => $total_price,
            'sale_type' => $request->sale_type,
        ]);
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'nullable|string',
            // other sale fields...
        ]);
    
        $customer = Customer::create([
            'name' => $validated['customer_name'],
            'phone' => $validated['customer_phone'],
            'address' => $validated['customer_address'] ?? '',
        ]);
    
        $sale = Sale::create([
            'customer_id' => $customer->id,
            // other sale fields...
        ]);
    
        // handle sale items etc.
    
        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully!');
        // Update inventory
        $product->decrement('quantity', $request->quantity);

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale->load('items');
        $products = Product::all();
        $customers = Customer::all();
        return view('sales.edit', compact('sale', 'products', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'discount_percent' => 'nullable|in:0,2,4,6',
            'sale_type' => 'required|in:cash,online,card',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Adjust stock: restore old, subtract new
        $product->increment('quantity', $sale->quantity); // rollback previous sale quantity
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available for updated sale.');
        }

        $base_price = $product->selling_price * $request->quantity;
        $discount = ($request->discount_percent ?? 0) / 100;
        $total_price = $base_price - ($base_price * $discount);

        $sale->update([
            'product_id' => $request->product_id,
            'customer_id' => $request->customer_id,
            'quantity' => $request->quantity,
            'discount_percent' => $request->discount_percent ?? 0,
            'total_price' => $total_price,
            'sale_type' => $request->sale_type,
        ]);

        // Subtract new quantity
        $product->decrement('quantity', $request->quantity);

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $product = Product::find($sale->product_id);
        if ($product) {
            $product->increment('quantity', $sale->quantity); // restore inventory
        }

        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
