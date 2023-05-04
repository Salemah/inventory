<?php

namespace App\Http\Controllers\Admin\Employee\Salary;

use App\Http\Controllers\Controller;
use App\Models\Account\BankAccount;
use App\Models\Account\Transaction;
use App\Models\Employee\Employee;
use App\Models\Employee\Salary\SalaryReport;
use App\Repositories\Admin\Account\AccountsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryReportController extends Controller
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
                $salaries = SalaryReport::latest()->with('employee','account')->get();

                return DataTables::of($salaries)
                    ->addIndexColumn()
                    ->addColumn('employee_id',function ($salaries){
                        $emp_name = $salaries->employee->name;
                        return $emp_name;
                    })
                    ->addColumn('current_salary',function ($salaries){
                        $currentSalarie=$salaries->employee->gross_salary;
                        return $currentSalarie;
                    })
                    ->addColumn('account_id',function ($salaries){
                        $account = $salaries->account->name;
                        return $account;
                    })
                    ->addColumn('status', function ($salaries) {
                        if ($salaries->status == 1) {
                            return '<button
                            onclick="showStatusChangeAlert(' . $salaries->id . ')"
                             class="btn btn-sm btn-primary">Active</button>';
                        } else {
                            return '<button
                            onclick="showStatusChangeAlert(' . $salaries->id . ')"
                            class="btn btn-sm btn-warning">In-active</button>';
                        }
                    })
                    ->addColumn('action', function ($salaries) {
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <a class="btn btn-sm btn-success text-white " style="cursor:pointer"
                        href="' . route('admin.salaryReport.edit', $salaries->id) . '" title="Edit"><i class="bx bxs-edit"></i></a><a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showDeleteConfirm(' . $salaries->id . ')" title="Delete"><i class="bx bxs-trash"></i></a>
                            </div>';
                    })
                    ->rawColumns(['employee_id','current_salary','account_id','status', 'action'])
                    ->make(true);
            }
            return view('admin.employee.salary.salary-report.index');
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
        $employees = Employee::all();
        $cash_account = BankAccount::where('status', 1)->where('type',1)->get();
        $bankAccounts = BankAccount::where('status', 1)->where('type',2)->get();
        return view('admin.employee.salary.salary-report.create',compact('bankAccounts','cash_account','employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'amount' => 'required'
        ]);
        if ($request->transaction_way) {
            if ($request->transaction_way == 2) {
                $request->validate([
                    'account_id' => 'required',
                    'month' => 'required',
                    'transaction_way' => 'required',
                ]);
            }
            else if ($request->transaction_way == 1) {
                $request->validate([
                    'cash_account_id' => 'required',
                ]);
            }
        }
        DB::beginTransaction();
        try{
            $account_id = 0;
            if ($request->transaction_way) {
                $request->transaction_way == 2 ? $account_id = $request->account_id : $account_id = $request->cash_account_id ;
                $balance = AccountsRepository::postBalance($account_id);

                $balance = $balance - $request->total_balance;

                if ($balance < 0) {
                    return redirect()->back()->with('error', 'Transaction failed for insufficient balance! ');
                }
            }
            $salary=new SalaryReport();
            $salary->employee_id = $request->employee_id;
            if ($request->transaction_way == 2) {
                $salary->account_id = $request->account_id;
                $salary->transaction_way = 2;
            }
            else if($request->transaction_way == 1){
                $salary->account_id = $request->cash_account_id;
                $salary->transaction_way = 1;
            }
            $salary->amount = $request->amount;
            $salary->month = $request->month;
            $salary->cheque_number = $request->cheque_number;
            $salary->save();


            $transaction = new Transaction();
            $transaction->transaction_title = "Employee Salary";

            if ($request->transaction_way == 2) {
                $transaction->account_id = $request->account_id;
                $transaction->transaction_account_type = 2;
            }
            else if($request->transaction_way == 1){
                $transaction->account_id = $request->cash_account_id;
                $transaction->transaction_account_type = 1;
            }
            $transaction->transaction_purpose = 17;
            $transaction->transaction_type = 1;
            $transaction->amount = $request->amount;
            $transaction->emp_salaryId = $salary->id;
            $transaction->cheque_number = $request->cheque_number;
            $transaction->status = 0;

            if($transaction->created_by){
                $transaction->updated_by = Auth::user()->id;
                $transaction->access_id = json_encode(UserRepository::accessId(Auth::id()));
            }else{
                $transaction->created_by= Auth::user()->id;
            }
            $transaction->save();

            DB::commit();
            return redirect()->route('admin.salaryReport.index')->with('message', 'Employee salary created  Successfully');
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statusUpdate()
    {
        //
    }

    public function reportList()
    {
        $employees = Employee::all();
        return view('admin.employee.salary.salary-report.report-list',compact('employees'));
    }

    public function reportListShow(Request $request)
    {
        $employee = $request->employee_id;
        $date_form = $request->month_from;
        $date_form = $request->month_to;
        $salaries = SalaryReport::where()->with('employee','account');
        return view('admin.employee.salary.salary-report.salary-list-view');
    }
}
