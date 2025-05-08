<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('inventory', 'vendor')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('products.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'capacity' => 'required|string',
                'warranty_months' => 'nullable|integer',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'vendor_id' => 'required|exists:vendors,id',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        $data = $request->all();

        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('products', 'public');
            $data['product_image'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $vendors = Vendor::all();
        return view('products.edit', compact('product', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'required|string',
            'warranty_months' => 'nullable|integer',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'vendor_id' => 'required|exists:vendors,id',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        $data = $request->all();

        if ($request->hasFile('product_image')) {
            // delete old image if it exists
            if ($product->product_image && \Storage::disk('public')->exists($product->product_image)) {
                \Storage::disk('public')->delete($product->product_image);
            }

            $path = $request->file('product_image')->store('products', 'public');
            $data['product_image'] = $path;
        }

        $product->update($data);


        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
