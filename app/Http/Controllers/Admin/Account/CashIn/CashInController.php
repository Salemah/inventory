<?php

namespace App\Http\Controllers\Admin\Account\CashIn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account\BankAccount;
use App\Models\Account\Transaction;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use DataTables;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $cashIns = Transaction::latest()->where(['transaction_account_type' => 1, 'transaction_purpose' => 8])->with('createdByUser')->get();
                return DataTables::of($cashIns)
                    ->addIndexColumn()

                    ->addColumn('createdByUser', function ($cashIns) {
                        return $cashIns->createdByUser->name;
                    })
                    ->addColumn('purpose', function ($cashIns) {
                        if ($cashIns->transaction_purpose == 8) {
                            return '<span class="text-sucess">Cash-in</span>';
                        }
                    })
                    ->addColumn('action', function ($cashIns) {
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a class="btn btn-sm btn-success text-white " style="cursor:pointer" href="' . route('admin.account.cash-in.edit', $cashIns->id) . '" title="Edit"><i class="bx bxs-edit"></i></a><a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showTransactionDeleteConfirm(' . $cashIns->id . ')" title="Delete"><i class="bx bxs-trash"></i></a> </div>';
                    })
                    ->rawColumns(['action','createdByUser', 'purpose'])
                    ->make(true);
            }
            return view('admin.account.cash_in.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // Get All Bank
            $bank_accounts = BankAccount::where('type', 1)->where('status', 1)->get();
            return view('admin.account.cash_in.create',compact('bank_accounts'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('exception', 'Operation failed ! ' . $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'cash_account_id' => 'required',
            'transaction_title' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required',
        ]);
        try {
            //  Transaction Store
            $transaction = new Transaction();
            $transaction->transaction_title = $request->transaction_title;
            $transaction->transaction_date = $request->transaction_date;
            $transaction->account_id = $request->cash_account_id;
            $transaction->transaction_purpose =8;
            $transaction->transaction_type = 2;
            $transaction->transaction_account_type = 1;
            $transaction->amount = $request->amount;
            $transaction->description =strip_tags($request->description);
            $transaction->created_by = Auth::user()->id;
            $transaction->access_id = json_encode(UserRepository::accessId(Auth::id()));

            $transaction->save();

           return redirect()->route('admin.account.cash-in.index')->with('message', 'Cash In Successfully.');
        } catch (\Exception $exception) {
           return redirect()->back()->with('exception', 'Operation failed ! ' . $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $bank_accounts = BankAccount::where('type', 1)->where('status', 1)->get();
            $cashIn = Transaction::where('id', $id)->first();
           return view('admin.account.cash_in.edit',compact('cashIn','bank_accounts'));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return redirect()->back()->with('exception', 'Operation failed ! ' . $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cash_account_id' => 'required',
            'transaction_title' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required',
        ]);
        try {
            //  Transaction Store
            $transaction = Transaction::findOrFail($id);

            $transaction->transaction_title = $request->transaction_title;
            $transaction->transaction_date = $request->transaction_date;
            $transaction->account_id = $request->cash_account_id;
            $transaction->transaction_purpose = 8;
            $transaction->transaction_type = 2;
            $transaction->transaction_account_type = 1;
            $transaction->amount = $request->amount;
            $transaction->description =strip_tags($request->description);
            $transaction->updated_by = Auth::user()->id;

            $transaction->update();

           return redirect()->route('admin.account.cash-in.index')->with('message', 'Cash In Update successfully.');
        } catch (\Exception $exception) {
           return redirect()->back()->with('exception', 'Operation failed ! ' . $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax()) {
            try {
                Transaction::findOrFail($id)->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Cash In Deleted Successfully.',
                ]);
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
            }
        }
    }

    /**
     * Update the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
}
