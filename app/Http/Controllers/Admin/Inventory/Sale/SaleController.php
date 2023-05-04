<?php

namespace App\Http\Controllers\Admin\Inventory\Sale;

use DataTables;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account\BankAccount;
use App\Models\Account\Transaction;
use App\Models\Employee\Employee;
use App\Models\Inventory\Customers\InventoryCustomer;
use App\Models\Inventory\Products\InventoryProductCount;
use App\Models\Inventory\Products\Products;
use App\Models\Inventory\Products\ProductVariant;
use App\Models\Inventory\Products\ProductWarehouse;
use App\Models\Inventory\Products\Variant;
use App\Models\Inventory\Purchase\PriceManagement;
use App\Models\Inventory\Purchase\Purchase;
use App\Models\Inventory\Sales\Sales;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory\Services\InventoryServiceCategory;
use App\Models\Inventory\Settings\InventoryUnit;
use App\Models\Inventory\Settings\InventoryWarehouse;
use App\Models\Inventory\Settings\Taxes;
use App\Models\Inventory\Suppliers\InventorySupplier;
use App\Models\User;

class SaleController extends Controller
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
                // $Warehouse = Warehouse::latest()->get();
                $products = Sales::get();
                return DataTables::of( $products)
                    ->addIndexColumn()
                    ->addColumn('action', function ($products) {
                        return '<div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                  <a href="' . route('admin.inventory.sale.edit', $products->id) . '" class="btn btn-sm btn-success text-white" style="cursor:pointer" title="Edit"><i class="bx bxs-edit"></i></a>
                                  <button class="btn btn-sm btn-primary text-white" style="cursor:pointer" title="Add Payment" data-coreui-toggle="modal" data-coreui-target="#exampleModal" onclick="getSelectedUserData(' . $products->id . ')" ><i class="bx bx-money" style="color: #FC3207;"></i></button>
                                  <a class="btn btn-sm btn-danger text-white" style="cursor:pointer" type="submit" onclick="showDeleteConfirm(' .$products->id . ')" title="Delete"><i class="bx bxs-trash"></i></a>
                            </div>';
                    })
                    ->addColumn('customer', function ($products) {
                        return $products->customers->name;
                    })
                    ->addColumn('paid', function ($products) {
                        return number_format($products->paid_amount,2);
                    })
                    ->addColumn('grand_total', function ($products) {
                        return number_format($products->grand_total,2);
                    })
                    ->addColumn('due', function ($products) {

                        return  number_format($products->grand_total - $products->paid_amount,2);
                    })


                    ->rawColumns(['grand_total','due','paid','action','customer'])
                    ->make(true);
            }
            $cash_account = BankAccount::where('status', 1)->where('type',1)->get();
            $bankAccounts = BankAccount::where('status', 1)->where('type',2)->get();
            return view('admin.inventory.sale.index',compact('cash_account','bankAccounts'));
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
            $warehouses = InventoryWarehouse::get();
            $variantProducts = ProductVariant::with('products','varients')->get();
            $products = Products::with('productVarients')->get();

            $auth = Auth::user();
            $user_role = $auth->roles->first();

            if($user_role->name == 'Super Admin' || $user_role->name == 'Admin' ){
                $mystore = '';
            }
            else{
                $user=User::where('id',Auth::user()->id)->where('user_type',1)->first();
                $employee=Employee::where('id',$user->user_id)->first();
                $mystore=InventoryWarehouse::where('id',$employee->warehouse )->first();
            }

            return view('admin.inventory.sale.create',compact('warehouses','taxs','variantProducts','products','mystore'));
        }
        catch (\Exception $exception) {
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
        //dd($request->all());
        $request->validate([
            'cash_memo' => 'required|unique:purchases,cash_memo',
            'date' => 'required',
            'invoice_no' => 'required|unique:purchases,invoice_no',
            'warehouse_id' => 'required',
        ]);
        try {
            $data = new Sales();

            if (isset($request->document)){
                $file = $request->file('document');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/inventory/sale/documents/'), $filename);
                $data->document = $filename;
            }

            $data->reference_no='pr-' . date("Ymd") . '-'. date("his");
            $data->warehouse_id=$request->warehouse_id;

            $data->customer_id=$request->customer_id;
            $data->total_qty=$request->total_qty;
            $data->cash_memo=$request->cash_memo;
            $data->date=$request->date;
            $data->invoice_no=$request->invoice_no;
            $data->shipping_cost=$request->shipping_cost;
            $data->grand_total=$request->total;
            $data->paid_amount=0.00;
            $data->payment_status=1;
            $data->status=$request->status;
            $data->note=$request->note;
            $data->created_by=Auth::user()->id;
            $data->access_id=json_encode(UserRepository::accessId(Auth::id()));
            $data->save();

            $product_id = $request->product;
            foreach ($product_id as $i => $id) {
                $warehouse = new InventoryProductCount();

                $warehouse->reference_no='pr-' . date("Ymd") . '-'. date("his");
                $warehouse->date=$request->date;
                $warehouse->warehouse_id=$request->warehouse_id;
                $warehouse->product_id=$id;
                $warehouse->sale_id=$data->id;
                $warehouse->sale_qty=$request->qty[$i];
                $warehouse->stock_out = $request->qty[$i];
                $warehouse->customer_id=$request->customer_id;
                $warehouse->product_batch_id=$request->batch_no[$i];
                $warehouse->expired_date=$request->expired_date[$i];
                $warehouse->variant_id=$request->variant[$i];
                $warehouse->purchase_unit_id=$request->purchase_unit[$i];

                if($request->order_tax_rate != null){
                    $warehouse->tax_rate=$request->order_tax_rate;
                }
                    $warehouse->discount=$request->discount[$i];
                $warehouse->net_unit_cost=$request->price[$i];
                $warehouse->selling_price=$request->price[$i];
                $warehouse->total=$request->subtotal[$i];

                $warehouse->status=$request->status;
                $warehouse->created_by=Auth::user()->id;
                $warehouse->access_id=json_encode(UserRepository::accessId(Auth::id()));
                $warehouse->save();

            }

            return redirect()->route('admin.inventory.sale.index')
                    ->with('toastr-success', 'Sale Successfully');
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
            $taxs =  Taxes::where('status',1)->get();
            $warehouses = InventoryWarehouse::get();
            // $variantProducts = ProductVariant::with('products','varients')->get();
            //$products = Products::with('productVarients')->get();

            $auth = Auth::user();
            $user_role = $auth->roles->first();

            if($user_role->name == 'Super Admin' || $user_role->name == 'Admin' ){
                $mystore = '';
            }
            else{
                $user=User::where('id',Auth::user()->id)->where('user_type',1)->first();
                $employee=Employee::where('id',$user->user_id)->first();
                $mystore=InventoryWarehouse::where('id',$employee->warehouse )->first();
            }

            $sale = Sales::with('customers')->findOrFail($id);
            $inventories = InventoryProductCount::with('products')->where('sale_id',$id)->get();
            //dd($inventories);
            return view('admin.inventory.sale.edit',compact('warehouses','taxs','mystore','sale','inventories'));
        }
        catch (\Exception $exception) {
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
         $request->validate([
            'cash_memo' => 'required|unique:purchases,cash_memo,'.$id,
            'date' => 'required',
            'invoice_no' => 'required|unique:purchases,invoice_no,'.$id,
            'warehouse_id' => 'required',
        ]);
         //file

        try {
            $data =Sales::findOrFail($id);

            if (isset($request->document)){
                $file = $request->file('document');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/inventory/sale/documents/'), $filename);
                $data->document = $filename;
            }

            $data->reference_no='pr-' . date("Ymd") . '-'. date("his");
            $data->warehouse_id=$request->warehouse_id;

            $data->customer_id=$request->customer_id;
            $data->total_qty=$request->total_qty;
            $data->cash_memo=$request->cash_memo;
            $data->date=$request->date;
            $data->invoice_no=$request->invoice_no;
            $data->shipping_cost=$request->shipping_cost;
            $data->grand_total=$request->total;
            $data->paid_amount=0.00;
            $data->payment_status=1;
            $data->status=$request->status;
            $data->note=$request->note;
            $data->created_by=Auth::user()->id;
            $data->access_id=json_encode(UserRepository::accessId(Auth::id()));
            $data->update();

            $product_id = $request->product;
            $warehouses = InventoryProductCount::where('sale_id',$id)->get();

            foreach ($warehouses as $i => $productCount) {
                $productCount->delete();
            }
            foreach ($product_id as $i => $id) {
                $warehouse = new InventoryProductCount();
                $warehouse->reference_no='pr-' . date("Ymd") . '-'. date("his");
                $warehouse->date=$request->date;
                $warehouse->warehouse_id=$request->warehouse_id;
                $warehouse->product_id=$id;
                $warehouse->sale_id=$data->id;
                $warehouse->sale_qty=$request->qty[$i];
                $warehouse->stock_out = $request->qty[$i];
                $warehouse->customer_id=$request->customer_id;
                $warehouse->product_batch_id=$request->batch_no[$i];
                $warehouse->expired_date=$request->expired_date[$i];
                $warehouse->variant_id=$request->variant[$i];
                $warehouse->purchase_unit_id=$request->purchase_unit[$i];

                if($request->order_tax_rate != null){
                    $warehouse->tax_rate=$request->order_tax_rate;
                }
                    $warehouse->discount=$request->discount[$i];
                $warehouse->net_unit_cost=$request->price[$i];
                $warehouse->selling_price=$request->price[$i];
                $warehouse->total=$request->subtotal[$i];

                $warehouse->status=$request->status;
                $warehouse->created_by=Auth::user()->id;
                $warehouse->access_id=json_encode(UserRepository::accessId(Auth::id()));
                $warehouse->save();

            }

            return redirect()->route('admin.inventory.sale.index')
                    ->with('toastr-success', 'Sale Update Successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function receivedPayment(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);
        try{
        $data = $request->all();
         $sale_data = Sales::findOrFail($request->sale_id);
         if( $sale_data->paid_amount){
            $sale_data->paid_amount =$sale_data->paid_amount+ $request->paying_amount;
         }
         else{
            $sale_data->paid_amount = $request->paying_amount;
         }
         $sale_data->update();

         $transaction = new Transaction();
         $transaction->transaction_title = "Sale Payment Receive";
         $transaction->transaction_date = $request->date;

         if ($request->transaction_way == 2) {
              $transaction->account_id = $request->account_id;
             $transaction->transaction_account_type = 2;
         }
         else if($request->transaction_way == 1){
             $transaction->account_id = $request->cash_account_id;
             $transaction->transaction_account_type = 1;
         }
         $transaction->transaction_purpose = 18;
         $transaction->transaction_type = 2;
         $transaction->amount = $request->paying_amount;
         $transaction->supplier_id  = $sale_data->supplier_id;
         $transaction->purchase_id  = $sale_data->id;
         $transaction->cheque_number = $request->cheque_number;
         $transaction->description = $request->note;
         $transaction->status = 1;

        $transaction->created_by= Auth::user()->id;
        $transaction->access_id = json_encode(UserRepository::accessId(Auth::id()));

         $transaction->save();
         return redirect()->route('admin.inventory.sale.index')
         ->with('toastr-success', 'Payment Receive Successfully');
        }
        catch (\Exception $exception) {
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
                $sale =  Sales::findOrFail($id);
                $inventory = InventoryProductCount::where('sale_id',$sale->id)->first();
                //$product = Products::findOrFail($inventory->product_id);
                $inventries = InventoryProductCount::where('sale_id',$sale->id)->get();
                foreach($inventries as $inventry){
                    $inventry->delete();
                }
                $transactions = Transaction::where('sale_id',$sale->id)->get();
                foreach($transactions as $transaction){
                    $transaction->delete();
                }
                // $product->qty= 0;
               // $product->update();
                $sale->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Sale Deleted Successfully.',
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
            $serviceCategory = InventoryServiceCategory::findOrFail($id);
            // Check Item Current Status
            if ($serviceCategory->status == 1) {
                $serviceCategory->status = 0;
            } else {
                $serviceCategory->status = 1;
            }

            $serviceCategory->save();
            return response()->json([
                'success' => true,
                'message' => 'Category of Service Status Updated Successfully.',
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function productSearch(Request $request)
    {
        //\dd($request->all());
       // $result = Products::query()
                        // ->limit(10)
                        // ->where('name', 'LIKE', "%{$request->search}%")
                        // ->get(['name', 'id']);
        //$purchase = Purchase::where('id',$request->warehouse_id)->first();
        //
        // $result = [];
        $inventories = InventoryProductCount::with('products','variant')
                                        ->where('warehouse_id',$request->warehouse_id)
                                        ->where('purchase_id', '<>', '', 'and')
                                        ->where('purchase_return_id',null)
                                        ->get();
                                    //dd($inventories);
        foreach( $inventories as  $key=>$inventory){
                if($inventory->variant_id){
                    $variants = Variant::findOrFail($inventories[$key]->variant->id);
                    $final = array_merge(array( $inventory),array($variants));
                    $result[] = $final;
                }
                else{
                    $result[] = $inventory;
                }
        }
        // \dd($request->warehouse_id);
        // $resultt=array_diff($inventories,$result);
        return $result;
    }
    public function customerSearch(Request $request)
    {
        $result = InventoryCustomer::query()
                        ->limit(10)
                        ->where('name', 'LIKE', "%{$request->search}%")
                        ->get(['name', 'id']);

        return $result;
    }
    public function supplierSearch(Request $request)
    {
        $result = InventorySupplier::query()
                        ->limit(10)
                        ->where('name', 'LIKE', "%{$request->search}%")
                        ->get(['name', 'id']);
        return $result;
    }
    public function productData(Request $request,$id)
    {
        $price = [];
        $productVariants = [];
        $variants = [];
        $result[] = Products::with('taxs')->findOrFail($id);
        $priceData = PriceManagement::where('product_id',$id)->latest()->first();


        if(isset($request->data))
       {
        $productVariant = ProductVariant::findOrFail($request->data);
        $variant = Variant::findOrFail($productVariant->variant_id);
        $productVariants[] = $productVariant;
        $variants[] = $variant;
       }
       $variantData=array_merge($productVariants,$variants);

        if($priceData){
            $price[] =  $priceData;
        }
        else{
            $price[] = [
                "id" => 0,
                "product_id" => 0,
                "cost" => 0,
                "price" => 0,
                ];
        }
        $obj_merged =  array_merge($result,$price);
        $allData =  array_merge($obj_merged,$variantData);

        return json_encode( $allData);
    }
    public function warehouseSearch(Request $request, $id)
    {
        if ($request->ajax()) {
            $warehouse = InventoryWarehouse::find($id);
            return response()->json($warehouse);
        }
    }
    public function saleSearch(Request $request, $id)
    {
        try {
            $sale = Sales::findOrFail($id);
            return response()->json(['data' => $sale]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}




