<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Exports\EmployeeExport;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Account\Bank;
use App\Models\Account\BankAccount;
use App\Models\BloodGroup;
use App\Models\CRM\Address\City;
use App\Models\CRM\Address\Country;
use App\Models\CRM\Address\State;
use App\Models\CRM\Client\Client;
use App\Models\CRM\Client\ClientComment;
use App\Models\CRM\Client\ClientReminder;
use App\Models\Employee\Department;
use App\Models\Employee\Designation;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeCertificate;
use App\Models\Employee\EmployeeDocuments;
use App\Models\Employee\EmployeeIdentity;
use App\Models\Employee\EmployeeQualification;
use App\Models\Employee\EmployeeReference;
use App\Models\Employee\EmployeeWorkExperience;
use App\Models\Identity;
use App\Models\Inventory\Settings\InventoryWarehouse;
use App\Models\Settings\Priority;
use App\Models\Settings\Reference;
use App\Models\User;
use App\Repositories\Admin\DateTime;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
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
                // if(Auth::id() == 1){
                    $Employee = Employee::with('departments', 'designations')->latest()->get();

                // }else{
                //     $userId= User::findOrFail(Auth::id());
                //     $Employee = Employee::with('departments', 'designations')->where('id',$userId->user_id)->get();
                // }
                //$Employee = Employee::with('departments', 'designations')->latest()->get();
                return DataTables::of($Employee)
                    ->addIndexColumn()
                    ->addColumn('name', function ($Employee) {
                        return '<a class="text-primary" style="cursor:pointer;text-decoration: none;"
                        href="' . route('admin.employee.show', $Employee->id) . '"> ' . $Employee->name . ' </a>';
                    })
                    ->addColumn('dept', function ($Employee) {
                        return $Employee->departments->name;
                    })
                    ->addColumn('designation', function ($Employee) {
                        return $Employee->designations->name;
                    })
                    ->addColumn('image', function ($Employee) {
                        $url = asset('img/employee/' . $Employee->image);
                        $url2 = asset('img/no-image/noman.jpg');
                        if ($Employee->image) {
                            return '<img src="' . $url . '" border="0" width="40"  align="center" />';
                        }
                        return '<img src="' . $url2 . '" border="0" width="40"  align="center" />';

                    })
                    ->addColumn('action', function ($Employee) {
                        if (Auth::user()->can('employee_delete')){
                            $deleteBtn = '<a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showDeleteConfirm(' . $Employee->id . ')" title="Delete"> <i class="bx bxs-trash"></i></a>';
                        }else{
                            $deleteBtn = '';
                        }
                        if (Auth::user()->can('employee_show')){
                            $showBtn = '<a class="btn btn-sm btn-success text-white " title="Show" style="cursor:pointer"
                            href="' . route('admin.employee.show', $Employee->id) . '"><i class="bx bx-user "> </i> </a>';
                        }else{
                            $showBtn = '';
                        }
                        if (Auth::user()->can('employee_profile')){
                            $profileBtn = ' <a class="btn btn-sm btn-info text-white " title="Profile"style="cursor:pointer"href="' . route('admin.employee.profile', $Employee->id) . '"><i class="bx bx-show"></i> </a>';
                        }else{
                            $profileBtn = '';
                        }

                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'.$showBtn.$profileBtn.$deleteBtn.
                           '</div>';
                    })
                    ->rawColumns(['name', 'image', 'dept', 'designation', 'action'])
                    ->make(true);
            }
            return view('admin.employee.index');
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
            $years = DateTime::getYear();
            $references = Reference::where('status', 1)->get();
            $departments = Department::where('status', 1)->get();
            $designations = Designation::where('status', 1)->get();
            $roles = Role::get();
            $warehouses = InventoryWarehouse::get();

            return view('admin.employee.create', compact('references', 'departments', 'designations', 'years','roles','warehouses'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
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
        // Validation Start
        $request->validate([
            'name' => 'required',
            'employee_id' => 'required|unique:employees,employee_id',
            'designation' => 'required',
            'department' => 'required',
            'joining_date' => 'required',
            'joining_salary' => 'required',
            'role' => 'required',
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL',
            'phone_primary' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL',
        ]);
        // Validation End

        // Store Data
        DB::beginTransaction();
        try {
            $data = new Employee();
            $data->name = $request->name;
            $data->employee_id = $request->employee_id;
            $data->email = $request->email;
            $data->phone_primary = $request->phone_primary;
            $data->joining_date = $request->joining_date;
            $data->joining_salary = $request->joining_salary;
            $data->warehouse = $request->warehouse;
            $data->role = $request->role;
            $data->designation = $request->designation;
            $data->department = $request->department;
            $data->access_id = json_encode(UserRepository::accessId(Auth::id()));
            $data->created_by = Auth::id();

            $data->save();

            //store data user table
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->phone_primary;
            $user->password = Hash::make('employee');
            $user->user_id = $data->id;
            $user->user_type = 1; // employee userType = 1
            $user->record_access = 1;

            $user->role_id = $request->role;
            $user->access_id = json_encode(UserRepository::accessId(Auth::id()));
            $user->created_by = Auth::id();
            $user->save();
            //$user->assignRole(3);

            DB::table('model_has_roles')->insert([
                'role_id' => $request->role,
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id,
            ]);

            DB::commit();
            return redirect()->route('admin.employee.index')->with('message', 'Create successfully.');
        } catch (\Exception $exception) {

            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        try {
            $years = DateTime::getYear();
            $bloodgroups = BloodGroup::get();
            $identities = Identity::where('status', 1)->get();
            $EmployeeIdentity = EmployeeIdentity::where('employee_id', $employee->id)->where('user_type', 1)->get();
            $banks = Bank::where('status', 1)->get();
            $warehouses = InventoryWarehouse::get();
            return view('admin.employee.show', compact('employee', 'years', 'bloodgroups', 'identities', 'EmployeeIdentity', 'banks','warehouses'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
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
        $user = User::where('user_id', $id)->where('user_type', 1)->first();
        $request->validate([
            'name' => 'required',
            'employee_id' => 'required',
            'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL'.$user->user_id,
            'phone_primary' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL'.$user->user_id,
            'designation' => 'required',
            'department' => 'required',
            'joining_date' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = Employee::findOrFail($id);
            $data->name = $request->name;
            $data->employee_id = $request->employee_id;
            $data->father_name = $request->father_name;
            $data->mother_name = $request->mother_name;
            $data->email = $request->email;
            $data->phone_primary = $request->phone_primary;
            $data->phone_secondary = $request->phone_secondary;
            $data->website = $request->website;
            $data->gender = $request->gender;
            $data->joining_date = $request->joining_date;
            $data->job_left_date = $request->job_left_date;
            $data->joining_salary = $request->joining_salary;
            $data->warehouse = $request->warehouse;
            $data->current_salary = $request->current_salary;
            $data->present_address = $request->present_address;
            $data->permanent_address = $request->permanent_address;
            $data->designation = $request->designation;
            $data->department = $request->department;
            $data->marital_status = $request->marital_status;
            $data->date_of_birth = $request->date_of_birth;
            $data->blood_group = $request->blood_group;
            $data->role = $request->role;

            $data->description = $request->description;
            $data->updated_by = Auth::id();

            if ($request->file()) {
                $file = $request->file('image');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/employee/'), $filename);
                $data->image = $filename;
            }
            $data->update();

            //store data user table
            $user = User::where('user_id', $id)->where('user_type', 1)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->phone_primary;
            $user->user_id = $data->id;
            $user->update();

            DB::commit();
            return redirect()->route('admin.employee.show',$id)->with('message', 'Update successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function EmployeeAddressUpdate(Request $request, $id)
    {
        $request->validate([
            'present_address' => 'required',
            'permanent_address' => 'required',
            'country' => 'required|numeric',
            'states' => 'required|numeric',
            'cities' => 'required|numeric',
            'zip' => 'required',
        ]);
        try {
            $data = Employee::findOrFail($id);
            $data->present_address = $request->present_address;
            $data->permanent_address = $request->permanent_address;
            $data->country_id = $request->country;
            $data->state_id = $request->states;
            $data->city_id = $request->cities;
            $data->zip = $request->zip;
            $data->update();
            return redirect()->route('admin.employee.show',$id)->with('message', 'Update successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $employee = Employee::findOrFail($id);
                if ($employee) {
                    $user = User::where('user_id', $id)->where('user_type', 1)->first();
                    DB::table('model_has_roles')->where('role_id', 3)->where('model_id', $user->id)->delete();
                    $user->delete();
                    $employee->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Employee Deleted Successfully.',
                    ]);
                }

            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
            }
        }
    }

    //starts status change function
    public function statusUpdate(Request $request)
    {
        $id = $request->id;
        $status_check = Employee::findOrFail($id);
        $status = $status_check->status;

        if ($status == 1) {
            $status_update = 0;
        } else {
            $status_update = 1;
        }

        $data = array();
        $data['status'] = $status_update;
        Employee::where('id', $id)->update($data);
        if ($status_update == 1) {
            return "success";
            // exit();
        } else {
            return "failed";
        }
    }

    public function departmentSearch(Request $request)
    {
        $result = Department::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);
        return $result;
    }

    public function designationSearch(Request $request)
    {
        $result = Designation::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);
        return $result;
    }

    public function identitySearch(Request $request)
    {
        $result = Identity::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);
        return $result;
    }

    public function referenceSearch(Request $request)
    {
        $result = Reference::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);
        return $result;
    }

    public function countrySearch(Request $request)
    {
        $result = Country::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);

        return $result;
    }

    public function stateSearch(Request $request)
    {
        $result = State::query()
            ->where('country_id', "$request->country_id")
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);

        return $result;
    }

    public function citySearch(Request $request)
    {
        $result = City::query()
            ->where('state_id', "$request->state_id")
            ->where('name', 'LIKE', "%{$request->search}%")
            ->get(['name', 'id']);

        return $result;
    }

    public function employeeProfile(Request $request, $id)
    {
        try {
            $employee = Employee::with('departments', 'designations', 'identities')->findOrFail($id);

            $documents = EmployeeDocuments::where('employee_id', $id)->where('user_type', 1)->get();

            $employeeIdentity = EmployeeIdentity::where('employee_id', $id)
                ->where('user_type', 1)
                ->with('identity')
                ->get();
            $employeeQualifications = EmployeeQualification::where('employee_id', $id)
                ->where('user_type', 1)
                ->get();
            $employeeWorkExperiences = EmployeeWorkExperience::where('employee_id', $id)
                ->where('user_type', 1)
                ->get();
            $employeeCertifications = EmployeeCertificate::where('employee_id', $id)
                ->where('user_type', 1)
                ->get();

            $employeeReferences = EmployeeReference::where('employee_id', $id)->where('user_type', 1)
                ->with('reference')
                ->get();

            $bankAccounts = BankAccount::where('user_id', $id)->where('account_type', 2)->with('bank')->get();
            return view('admin.employee.profile', compact('employee', 'documents', 'employeeIdentity', 'employeeReferences', 'bankAccounts', 'employeeQualifications', 'employeeWorkExperiences', 'employeeCertifications'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function import()
    {
        Excel::import(new EmployeeImport,request()->file('file'));
        return redirect()->back()->with('message', 'Client Import successfully.');

    }
    public function export()
    {
        return Excel::download(new EmployeeExport, 'employee-list.xlsx');
    }
    public function employeePdf()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];

        $pdf = Pdf::loadView('admin.employee.profile-pdf', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }
    public function  workReport(Request $request){
        try{
            if ($request->ajax()) {

                    $Employee = Employee::with('departments', 'designations')->latest()->get();

                    return DataTables::of($Employee)
                    ->addIndexColumn()
                    ->addColumn('name', function ($Employee) {
                        return '<a class="text-primary" style="cursor:pointer;text-decoration: none;"
                        href="' . route('admin.employee.show', $Employee->id) . '"> ' . $Employee->name . ' </a>';
                    })
                    ->addColumn('own', function ($Employee) {
                        $user_id = User::where('user_type', 1)
                        ->where('user_id', $Employee->id)
                        ->first();

                        $Clients = Client::where('created_by',$user_id->id)
                                ->orwhereJsonContains('own_assign', $user_id->id)
                                ->whereJsonLength('assign_to',0)
                                ->count();
                        return $Clients;

                    })
                    ->addColumn('reminder', function ($Employee) {
                        $user_id = User::where('user_type', 1)
                        ->where('user_id', $Employee->id)
                        ->first();
                        $reminders = ClientReminder::where('created_by',$user_id->id)
                                    ->count();
                        return $reminders;
                    })
                    ->addColumn('comment', function ($Employee) {
                        $user_id = User::where('user_type', 1)
                        ->where('user_id', $Employee->id)
                        ->first();
                        $comments = ClientComment::where('created_by',$user_id->id)
                                    ->count();
                        return $comments;
                    })
                    ->addColumn('assign_client', function ($Employee) {
                        $user_id = User::where('user_type', 1)
                        ->where('user_id', $Employee->id)
                        ->first();
                        $Clients = Client::whereJsonContains('assign_to',$user_id->id)
                                    ->count();
                        return $Clients;
                    })

                    ->addColumn('action', function ($Employee) {
                            $profileBtn = ' <a class="btn btn-sm btn-info text-white " title="Profile"style="cursor:pointer"href="' . route('admin.employee.profile', $Employee->id) . '"><i class="bx bx-show"></i> </a>';
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'.$profileBtn.
                           '</div>';
                    })
                    ->rawColumns(['name','assign_client','comment',  'own', 'reminder', 'action'])
                    ->make(true);
            }
            return view('admin.work-report.employee-report');
        }
        catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
