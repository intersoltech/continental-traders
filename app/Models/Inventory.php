<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = ['product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // This method can be useful when incrementing inventory directly.
    public function incrementQuantity($quantity)
    {
        $this->quantity += $quantity;
        $this->save();
    }

    // This method can be useful for decrementing inventory.
    public function decrementQuantity($quantity)
    {
        $this->quantity -= $quantity;
        $this->save();
    }
}
