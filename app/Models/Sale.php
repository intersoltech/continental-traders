<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'payment_method',
        'discount',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems() {
    return $this->hasMany(SaleItem::class);
}

    public function product() {
        return $this->belongsTo(Product::class);
    }
    protected static function booted()
{
    static::created(function ($sale) {
        foreach ($sale->items as $item) {
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }
    });

    static::deleting(function ($sale) {
        foreach ($sale->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }
    });
}
}
