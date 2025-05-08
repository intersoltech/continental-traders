<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'warranty_months',
        'cost_price',
        'selling_price',
        'vendor_id',
        'product_image'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    // Method to update the product's inventory
    public function updateInventory($quantity)
    {
        // Check if inventory exists, if not create it
        $inventory = $this->inventory()->firstOrCreate(
            ['product_id' => $this->id], 
            ['quantity' => 0]
        );

        $inventory->incrementQuantity($quantity); // Or decrementQuantity() depending on the operation
    }
}
