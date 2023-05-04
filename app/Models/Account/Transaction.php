<?php

namespace App\Models\Account;

use App\Models\User;
use App\Models\Account\BankAccount;
use App\Models\Account\Investment\Investor;
use App\Models\Expense\Expense;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    public function createdByUser() {
        return $this->belongsTo(User::class, 'created_by');
    }
    // Relation With User Model
    public function updatedByUser() {
        return $this->belongsTo(User::class, 'updated_by');
    }
     // Relation With Bank-Account Model
     public function bankAccount() {
        return $this->belongsTo(BankAccount::class, 'account_id')->withTrashed();
    }
    public function investor() {
        return $this->belongsTo(Investor::class, 'investor_id', 'id');
    }
    public function expense() {
        return $this->belongsTo(Expense::class, 'expense_id', 'id');
    }
}
