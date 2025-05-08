<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('vendor')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::all();
        return view('purchases.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,online,card',
        ]);

        Purchase::create($request->all());

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully.');
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
        return view('purchases.edit', compact('purchase', 'vendors'));
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
