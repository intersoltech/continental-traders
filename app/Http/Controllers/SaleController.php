<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
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
            foreach ($request->products as $item) {
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
            foreach ($request->products as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
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

            // Re-validate inventory and calculate new total
            foreach ($request->products as $item) {
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
}
