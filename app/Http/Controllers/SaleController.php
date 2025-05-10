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
        $products = Product::with('inventory')->get(); // Fetch associated inventory
        $customers = Customer::all();
        return view('sales.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        // Validate the products array
        $request->validate([
            'customer_id' => 'required|string',
            'customer_name' => 'required|string',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',            
        ]);
    
        // Check if the customer exists or create a new one
        $customer = Customer::firstOrCreate([
            'id' => $request->customer_id
        ], [
            'phone' => $request->new_customer_phone,
            'name' => $request->customer_name,
            'address' => $request->new_customer_address,
        ]);
    
        // Calculate the total sale amount
        $totalAmount = 0;
        foreach ($request->products as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }
    
        // Create the sale record
        try {
            $sale = Sale::create([
                'customer_id' => $customer->id,
                'total' => $totalAmount - $request->discount, // Applying discount to total
                'payment_method' => $request->payment_method,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['sale_error' => 'An error occurred while saving the sale: ' . $e->getMessage()]);
        }
    
        // Loop through each product and create sale items, and update the inventory
        foreach ($request->products as $item) {
            // Fetch product and check inventory
            $product = Product::find($item['product_id']);
            $inventory = Inventory::where('product_id', $item['product_id'])->first();
    
            if (!$inventory || $inventory->quantity < $item['quantity']) {
                // Handle insufficient inventory
                return back()->withErrors(['inventory_error' => 'Not enough stock for product: ' . $product->name]);
            }
    
            // Create sale item entry
            try {
                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            } catch (\Exception $e) {
                return back()->withErrors(['sale_item_error' => 'Error saving sale item: ' . $e->getMessage()]);
            }
    
            // Update inventory after sale
            try {
                $inventory->decrement('quantity', $item['quantity']);
            } catch (\Exception $e) {
                return back()->withErrors(['inventory_update_error' => 'Error updating inventory: ' . $e->getMessage()]);
            }
        }
    
        return redirect()->route('sales.index')->with('success', 'Sale recorded and inventory updated successfully!');
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
