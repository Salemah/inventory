<?php

namespace App\Http\Controllers\Admin\Inventory\Purchase;

use DataTables;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Products\Products;
use App\Models\Inventory\Products\ProductWarehouse;
use App\Models\Inventory\Purchase\PriceManagement;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory\Services\InventoryServiceCategory;
use App\Models\Inventory\Settings\Taxes;
use App\Models\Inventory\Suppliers\InventorySupplier;
use Carbon\Carbon;

class PriceManagementController extends Controller
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
                $serviceCategories = PriceManagement::with('products')->groupBy('product_id')->latest()->get();
                foreach($serviceCategories as $price){
                    $stock_count = PriceManagement::with('products')->where('product_id',$price->product_id)->latest()->first();
                    $serviceCategory[] = $stock_count;
                }
                return DataTables::of($serviceCategory)
                    ->addIndexColumn()
                    ->addColumn('product', function ($serviceCategory) {
                        return $serviceCategory->products->name;
                    })
                    ->addColumn('code', function ($serviceCategory) {
                        return $serviceCategory->products->code;
                    })
                    ->addColumn('date', function ($serviceCategory) {
                        return  Carbon::parse($serviceCategory->created_at)->format('d/m/Y')  ;
                    })
                    ->addColumn('action', function ($serviceCategory) {
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                  <a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showDeleteConfirm(' . $serviceCategory->id . ')" title="Delete"><i class="bx bxs-trash"></i></a>
                            </div>';
                    })

                    ->rawColumns(['product','code','date','action'])
                    ->make(true);
            }
            return view('admin.inventory.purchases.price_management.index');
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
            $taxs =  Taxes::where('status',1)->get();
            $warehouses = ProductWarehouse::get();
            return view('admin.inventory.purchases.price_management.create',
                compact('warehouses','taxs'));
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
        $request->validate([
            'product_id'=>'required',
            'cost'=>'required',
            'price'=>'string',
        ]);
        try {
            $priceData = new  PriceManagement();
            $priceData->product_id = $request->product_id;
            $priceData->cost = $request->cost;
            $priceData->price =$request->price;
            $priceData->save();

            return redirect()->route('admin.inventory.price-management.index')
                    ->with('toastr-success', ' Created Successfully');
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
        //
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
            $serviceCategory     =   InventoryServiceCategory::findOrFail($id);
            $category            =   InventoryServiceCategory::where('id', $serviceCategory->parent_category)->first();
            $serviceCategories   =   InventoryServiceCategory::get();
            return view('admin.inventory.services.service-category.edit',
                compact('serviceCategory', 'serviceCategories', 'category'));
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
        // Validation Start
        $request->validate([
            'name'            =>      'required|string',
            'description'     =>      'string|nullable',
            'status'          =>      'required|numeric'
        ]);
        // Validation End

        // Store Data
        try {
            $data                       =       InventoryServiceCategory::where('id', $id)->first();
            $data->name                 =       $request->name;
            $data->category_code        =       $request->category_code;
            $data->parent_category      =       $request->parent_category;
            $data->description          =       strip_tags($request->description);;
            $data->status               =       $request->status;
            $data->updated_by           =       Auth::user()->id;
            $data->save();

            return redirect()->route('admin.inventory.services.category.index')
                    ->with('toastr-success', 'Service Category Updated Successfully');
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
                PriceManagement::findOrFail($id)->delete();
                return response()->json([
                    'success' => true,
                    'message' => ' Deleted Successfully.',
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


    public function productSearch(Request $request)
    {
        $result = Products::query()
                        ->limit(10)
                        ->where('name', 'LIKE', "%{$request->search}%")
                        ->get(['name', 'id']);

        return $result;
    }
    public function productPriceSearch(Request $request,$id)
    {
         $result = Products::findOrFail($id);
        return response()->json($result);
    }


}




