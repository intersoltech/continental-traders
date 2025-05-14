<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'discount',
        'total',
        'payment_method',
        'pdf_path',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(SaleItem::class);
    }

    protected static function booted()
    {
        static::created(function ($sale) {
            foreach ($sale->items as $item) {
                $inventory = $item->product->inventory;
                if ($inventory) {
                    $inventory->decrement('quantity', $item->quantity);
                }
            }
        });

        static::deleting(function ($sale) {
            foreach ($sale->items as $item) {
                $inventory = $item->product->inventory;
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }
        });
    }
}
