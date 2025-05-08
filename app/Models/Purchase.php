<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'purchase_date', 'total_amount', 'payment_method'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    protected static function booted()
{
    static::created(function ($purchase) {
        foreach ($purchase->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }
    });

    static::deleting(function ($purchase) {
        foreach ($purchase->items as $item) {
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }
    });
}
}
