<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Color;
use App\Models\Attrvalue;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\OrderProducts;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $data = Order::select(['orders.*','users.name','users.email','users.phone','users.address','users.city','users.state','products.product_name','products.unit_price','products.thumbnail_img','products.shipping_days',\DB::raw("GROUP_CONCAT(products.id) as p_id")])
        //             ->leftjoin('order_products','order_products.order_id','=','orders.id')
        //             ->leftjoin('products','products.id','=','order_products.product_id')
        //             ->leftjoin('users','orders.user','=','users.user_id')
        //             ->groupBy('orders.id')
        //             ->orderBy('id','desc')
        //             ->get();
        if($request->ajax()){
            $data = Order::select(['orders.*','users.name','users.email','users.phone','users.address','users.city','users.state','products.product_name','products.unit_price','products.thumbnail_img','products.shipping_days',\DB::raw("GROUP_CONCAT(products.id SEPARATOR '|||') as p_id"),\DB::raw("GROUP_CONCAT(order_products.product_delivery SEPARATOR ',') as delivery")])
                    ->leftjoin('order_products','order_products.order_id','=','orders.id')
                    ->leftjoin('products','products.id','=','order_products.product_id')
                    ->leftjoin('users','orders.user','=','users.user_id')
                    ->groupBy('orders.id')
                    ->orderBy('id','desc')
                    ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('order_id', function($row){
                    $order_id = 'ODR00'.$row->id;
                    if (strpos($row->delivery, '0') !== false) {
                        $order_id .= '<br><span class="text-danger">(Pending)</span>';
                    }
                    return $order_id;
                })
                ->editColumn('p_id', function($row){
                    $p_ids = array_filter(explode('|||',$row->p_id));
                    $products = '';
                    for($i=0;$i<count($p_ids);$i++){
                        $products .= '<li>PDR00'.$p_ids[$i].'</li>';
                    }
                    return $products;
                })
                ->editColumn('created_at', function($row){
                    return date('d F, Y',strtotime($row->created_at));
                })
                ->editColumn('amount', function($row){
                    return site_settings()->currency.$row->amount;
                })
                ->editColumn('created_at', function($row){
                    return date('d F, Y',strtotime($row->created_at));
                })
                ->editColumn('user_details', function($row){
                    $user_details = "<ul>
                                        <li><b>Name: </b> $row->name</li>          
                                        <li><b>Address: </b> $row->address</li>
                                    </ul>";
                    return $user_details;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="orders/'.$row->id.'/view_order" class="btn btn-success btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['p_id','order_id','user_details','action'])
                ->make(true);
        }
        return view('admin.orders.index');
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
        //
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

    public function changeDelivery(Request $request){
        $order_id = $request->post('order_id');
        $product_id = $request->post('product_id');
        $qty = $request->qty;
        Product::where('id',$product_id)->decrement('quantity',$qty);
        
        $order = OrderProducts::where('order_id',$order_id)->where('product_id',$product_id)->update([
            'product_delivery' => '1',
        ]);
        return $order;
    }

    public function view_order(Request $request,$id)
    {
        //
        $products = OrderProducts::select(['order_products.*','products.product_name','products.thumbnail_img'])
        ->leftJoin('products','products.id','=','order_products.product_id')
        ->where('order_id',$id)->get();
        // return $products;
        $attributes = Attribute::select('*')->get();
        $attrvalues = Attrvalue::select(['attrvalues.*','attributes.title'])
                         ->leftjoin('attributes','attributes.id','=','attrvalues.attribute')
                         ->get();
        $color = Color::select(['colors.*'])->get();

        $order = Order::find($request->id);
        return view('admin.orders.view_order',['products'=>$products,'order'=>$order,'attributes'=>$attributes,'attrvalues'=>$attrvalues,'colors'=>$color]);
    }

}
