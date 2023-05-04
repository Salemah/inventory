<?php

namespace App\Http\Controllers\admin\Employee\Salary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee\Salary\Salary;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'basic_salary'=>'required'
        ]);

       try{
        $data = new Salary();
        $data->allowance_id = $request->allowance_id;
        $data->basic_salary = $request->basic_salary;
        $data->gross_salary = $request->gross_salary;
        $data->employee_id = $request->employee_id;
        $data->description      =     $request->description;
        $data->created_by       =       Auth::user()->id;
        $data->access_id        =       json_encode(UserRepository::accessId(Auth::id()));
        $data->save();
        $LastStatus = Salary::latest()->first();
            $allStatus = Salary::where('id', '!=', $LastStatus->id)->get();
            foreach ($allStatus as $status) {
                if ($status->status == 1) {
                    $status->update([
                        'status' => 0
                    ]);
                }
            }
        return redirect()->back()->with('Succesfully Done');
    }catch(\Exception $exception){
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
        try {
            $salary = Salary::with('allowance')->findOrFail($id);
            return response()->json(['salary' => $salary]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salary::find($id);
        return view('admin.employee.salary.partial.edit', compact('salary'));
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
       $request->validate([
        'basic_salary'=>'required'
       ]);
       try{
        $data = Salary::find($request->salary_edit_id);
        $data->basic_salary = $request->basic_salary;
        $data->gross_salary = $request->gross_salary;
        $data->description      =     $request->description;
        $data->update();
        return redirect()->back()->with('Succesfully Done');
    }catch(\Exception $exception){
        return redirect()->back()->with('error', $exception->getMessage());
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $salary = Salary::findOrFail($id);
                $salary->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Salary Deleted Successfully.',
                ]);
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
            }
        }
    }
    public function salaryList(Request $request){
        try {
           if($request->ajax()){
                $salary = Salary::latest()->get();
                return DataTables::of($salary)
                ->addIndexColumn()
                ->setRowAttr([
                    'basic_salary' => function($salary) {
                        return $salary->basic_salary;
                    },
                    'allowance_salary' => function($salary) {
                        return $salary->gross_salary - $salary->basic_salary;
                    },
                    'salarEditId' => function($salary) {
                        return $salary->id;
                    },
                    'gross_salary' => function($salary) {
                        return $salary->gross_salary;
                    },
                    'class' => function() {
                        return 'mdlCreateBtn';
                    },
                    'description' => function($salary) {
                        return $salary->description;
                    },
                ])
                ->addColumn('date', function ($salary) {
                    return Carbon::parse($salary->created_at)->format("d F, Y");
                })
                ->addColumn('basic_salary', function ($salary) {
                    return $salary->basic_salary;
                })
                ->addColumn('gross_salary', function ($salary) {
                    return $salary->gross_salary;
                })
                ->addColumn('status', function ($salary) {
                    if ($salary->status == 1) {
                        $status = '<button type="submit" class="btn btn-sm btn-success mb-2 text-white" onclick="showStatusChangeAlert(' . $salary->id . ')">Active</button>';
                    } else {
                        $status = '<button type="submit" class="btn btn-sm btn-danger mb-2 text-white" onclick="showStatusChangeAlert(' . $salary->id . ')">Inactive</button>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($salary) {
                    return '<div class="btn-group" role="group" aria-label="Basic mixed styles example"><a type="button" class="btn btn-sm btn-primary text-white mdlCreateBtn" style="cursor:pointer"data-coreui-toggle="modal" data-coreui-target="#salaryEditSingleModal"><i class="bx bxs-edit"></i></a>
                    <span class="btn btn-sm btn-success text-white edit-city" style="cursor:pointer" onclick="getSelectedSalaryData(' . $salary->id . ')"  title="Show" data-coreui-toggle="modal" data-coreui-target="#salarySingleModal"><i class="bx bxs-show"></i></span>
                    <a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="salaryDeleteConfirm(' . $salary->id . ')" title="Delete"> <i class="bx bxs-trash"></i></a>
                        </div>';
                })
                ->rawColumns(['date','basic_salary','status','action'])
                ->make(true);
           }
        return view('admin.employee.salary.partial.salary-list');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        
    }

    
    public function statusUpdate(Request $request, $id)
    {
        try {
            $salary = Salary::findOrFail($id);
            // Check Item Current Status
            if ($salary->status == 1) {
                $salary->status = 0;
                $allStatus = Salary::where('id', '!=', $salary->id)->get();
                foreach ($allStatus as $status) {
                    if ($status->status == 1) {
                        $status->update([
                            'status' => 0
                        ]);
                    }
                }
            } else {
                $salary->status = 1;
                $allStatus = Salary::where('id', '!=', $salary->id)->get();
                foreach ($allStatus as $status) {
                    if ($status->status == 1) {
                        $status->update([
                            'status' => 0
                        ]);
                    }
                }
            }

            $salary->save();
            return response()->json([
                'success' => true,
                'message' => 'Allowance Status Update Successfully.',
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
