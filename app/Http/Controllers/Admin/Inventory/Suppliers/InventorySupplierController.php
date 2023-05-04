<?php

namespace App\Http\Controllers\Admin\Inventory\Suppliers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CRM\Address\City;
use Yajra\DataTables\DataTables;
use App\Models\CRM\Address\State;
use App\Models\CRM\Address\Country;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\CRM\Client\ClientType;
use App\Models\Inventory\Area\InventoryArea;
use App\Models\Inventory\Suppliers\InventorySupplier;

class InventorySupplierController extends Controller
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
                $suppliers = InventorySupplier::all();
                return DataTables::of($suppliers)
                    ->addIndexColumn()

                    ->addColumn('status', function ($suppliers) {
                        if ($suppliers->status == 1) {
                            $status = '<button type="submit" class="btn btn-sm btn-success mb-2 text-white" onclick="showStatusChangeAlert(' . $suppliers->id . ')">Active</button>';
                        } else {
                            $status = '<button type="submit" class="btn btn-sm btn-danger mb-2 text-white" onclick="showStatusChangeAlert(' . $suppliers->id . ')">Inactive</button>';
                        }
                        return $status;
                    })

                    ->addColumn('customer_type_priority', function ($suppliers) {
                        if ($suppliers->customer_type_priority == 1) {
                            return '<span style="color:#536DE7 ">First </span>';
                        } else if ($suppliers->customer_type_priority == 2) {
                            return '<span style="color:#536DE7 ">Second</span>';
                        } else if ($suppliers->customer_type_priority == 3) {
                            return '<span style="color:#536DE7 ">Third</span>';
                        } else {
                            return '--';
                        }
                    })

                    ->addColumn('action', function ($suppliers) {
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <a class="btn btn-sm btn-primary text-white " title="Show"style="cursor:pointer"href="' . route('admin.inventory.suppliers.supplier.show', $suppliers->id) . '"><i class="bx bx-show"> </i> </a>
                                    <a href="' . route('admin.inventory.suppliers.supplier.edit', $suppliers->id) . '" class="btn btn-sm btn-success text-white" style="cursor:pointer" title="Edit"><i class="bx bxs-edit"></i></a>
                                    <a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showDeleteConfirm(' . $suppliers->id . ')" title="Delete"><i class="bx bxs-trash"></i></a>
                                </div>';
                    })
                    ->rawColumns(['action', 'status', 'customer_type_priority'])
                    ->make(true);
            }
            return view('admin.inventory.suppliers.index');
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
            $countries   = Country::all();
            $areas       = InventoryArea::where('status', 1)->get();
            return view('admin.inventory.suppliers.create',
                compact('countries','areas'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'name.required'  => 'Enter supplier name',
            'phone.required'  => 'Enter phone number',
            'email.required'  => 'Enter email address',
            'address.required'  => 'Write your address',
        );

        $this->validate($request, array(
            'name' => 'required|string',
            'phone' => 'required||min:8|max:17|regex:/(01)[0-9]{9}/|unique:inventory_suppliers,phone,NULL,id,deleted_at,NULL',
            'email' => 'required|string|unique:inventory_suppliers,email,NULL,id,deleted_at,NULL',
            'address' => 'required|string',
        ), $messages);

        try {
            $data = new InventorySupplier();
            $data->country_id             = $request->country_id;
            $data->state_id               = $request->state_id;
            $data->city_id                = $request->city_id;
            $data->area_id                = $request->area_id;
            $data->postal_code            = $request->postal_code;
            $data->phone                  = $request->phone;
            $data->company_name           = $request->company_name;
            $data->name                   = $request->name;
            $data->email                  = $request->email;
            $data->tax_number             = $request->tax_number;
            $data->address                = $request->address;
            $data->description            = $request->description;
            $data->customer_type_priority = $request->customer_type_priority;
            $data->country_id             = $request->country_id;
            $data->updated_by             = Auth::user()->id;
            $data->access_id              = json_encode(UserRepository::accessId(Auth::id()));
            $data->save();

            return redirect()->route('admin.inventory.suppliers.supplier.index')
                    ->with('toastr-success', 'Supplier created successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $supplier = InventorySupplier::findOrFail($id);
        return view('admin.inventory.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $supplier  = InventorySupplier::findOrFail($id);
            $countries = Country::all();
            $states = State::where('country_id', $supplier->country_id)->get();
            $cities = City::where('state_id', $supplier->state_id)->get();
            $areas     = InventoryArea::where('status', 1)->get();
            return view('admin.inventory.suppliers.edit',
                compact('supplier','countries','states','cities','areas'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = array(
            'name.required'  => 'Enter customer name',
            'phone.required'  => 'Enter phone number',
            'email.required'  => 'Enter email address',
            'address.required'  => 'Write your address',
        );

        $this->validate($request, array(
            'name' => 'required|string',
            'phone' => 'required||min:11|max:11|regex:/(01)[0-9]{9}/|unique:inventory_suppliers,phone,' . $id . ',id,deleted_at,NULL',
            'email' => 'required|unique:inventory_suppliers,email,' . $id . ',id,deleted_at,NULL',
            'address' => 'required|string',
        ), $messages);

        try {
            $data = InventorySupplier::findOrFail($id);
            $data->country_id             = $request->country_id;
            $data->state_id               = $request->state_id;
            $data->city_id                = $request->city_id;
            $data->area_id                = $request->area_id;
            $data->postal_code            = $request->postal_code;
            $data->phone                  = $request->phone;
            $data->company_name           = $request->company_name;
            $data->name                   = $request->name;
            $data->email                  = $request->email;
            $data->tax_number             = $request->tax_number;
            $data->address                = $request->address;
            $data->description            = $request->description;
            $data->customer_type_priority = $request->customer_type_priority;
            $data->country_id             = $request->country_id;
            $data->updated_by             = Auth::user()->id;
            $data->access_id              = json_encode(UserRepository::accessId(Auth::id()));
            $data->update();

            return redirect()->route('admin.inventory.suppliers.supplier.index')
                    ->with('toastr-success', 'Supplier updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                InventorySupplier::findOrFail($id)->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier deleted successfully.',
                ]);
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', $exception->getMessage());
            }
        }
    }

    /**
     * Status Change the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function statusUpdate(Request $request, $id)
    {
        try {
            $supplier = InventorySupplier::findOrFail($id);
            if ($supplier->status == 1) {
                $supplier->status = 0;
            } else {
                $supplier->status = 1;
            }

            $supplier->update();
            return response()->json([
                'success' => true,
                'message' => 'Supplier status update successfully.',
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
