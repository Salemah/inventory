<?php

namespace App\Models\Revenue;

use App\Models\CRM\Client\Client;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends Model
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
    public function revenueBy(){
        return $this->belongsTo(Employee::class,'revenue_by')->withTrashed();
    }
    public function clientId(){
        return $this->belongsTo(Client::class,'client')->withTrashed();
    }
}
