<?php

namespace App\Repositories\Admin\Expense;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ExpenseRepository
{
    public static function transaction($transaction, $request, $expenseId){
        $transaction->transaction_title = "Expense";
        $transaction->transaction_date = $request->invoice_date;


        if ($request->transaction_way == 2) {
             $transaction->account_id = $request->account_id;
            $transaction->transaction_account_type = 2;
        }
        else if($request->transaction_way == 1){
            $transaction->account_id = $request->cash_account_id;
            $transaction->transaction_account_type = 1;
        }
        $transaction->transaction_purpose = 5;
        $transaction->transaction_type = 1;
        $transaction->amount = $request->total_balance;
        $transaction->expense_id = $expenseId;
        $transaction->cheque_number = $request->cheque_number;
        $transaction->description = $request->note;
        $transaction->status = 0;

        if($transaction->created_by){
            $transaction->updated_by = Auth::user()->id;
            $transaction->access_id = json_encode(UserRepository::accessId(Auth::id()));
        }else{
            $transaction->created_by= Auth::user()->id;
        }

        $transaction->save();
    }

}
