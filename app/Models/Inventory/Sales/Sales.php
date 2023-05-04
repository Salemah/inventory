<?php

namespace App\Models\Inventory\Sales;

use App\Models\Inventory\Customers\InventoryCustomer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory,SoftDeletes;
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation With User Model
    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function customers() {
        return $this->belongsTo(InventoryCustomer::class, 'customer_id');
    }
}
