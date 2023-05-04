<?php

namespace App\Models\Inventory\Products;

use App\Models\Inventory\Settings\InventoryWarehouse;
use App\Models\Inventory\Suppliers\InventorySupplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryProductCount extends Model
{
    use HasFactory,SoftDeletes;
     // Relation With User Model
     public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation With User Model
    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function suppliers() {
        return $this->belongsTo(InventorySupplier::class, 'supplier_id');
    }
    public function products() {
        return $this->belongsTo(Products::class, 'product_id');
    }
    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    public function warehouse() {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }
}
