<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FlashDeal;
use App\Models\FlashProduct;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FlashdealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()){
            $data = FlashDeal::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function($row){
                    if($row->flash_image != ''){
                        $img = '<img src="'.asset("flash-deals/".$row->flash_image).'" width="80px">';
                    }else{
                        $img = '<img src="'.asset("flash-deals/").'" width="100px">';
                    }
                    return $img;
                })
                ->editColumn('status', function($row){
                    if($row->status == '1'){
                        $status = '<span class="badge badge-success">Active</span>';
                    }else{
                        $status = '<span class="badge badge-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="flash-deals/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-flash-deal btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.flash-deals.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $products = Product::select(['products.*'])->get();
        return view('admin.flash-deals.create',['products'=>$products]);
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
        $request->validate([
            'title'=>'required',
            'img'=>'image|mimes:jpeg,png,jpg|max:2048',
            'datetimes'=>'required',
            'products'=>'required',
            'flash_status'=>'required',
        ]);

        if($request->img){
            $image = $request->img->getClientOriginalName();
            $request->img->move(public_path('flash-deals'),$image);
        }else{
            $image = '';
        }

        $slug = str_replace(array('_',' ',),'-',strtolower($request->input("title")));

        $flash = new FlashDeal();
        $flash->flash_title = $request->input('title');
        $flash->flash_image = $image;
        $flash->flash_date_range = $request->input('datetimes');
        $flash->flash_slug = $slug;
        $flash->status = $request->input('flash_status');
        $result = $flash->save();

        $product_id = $request->input('products');

        for($i=0; $i < count($product_id); $i++){
            $datasave = [
                'deals_id' => $flash->id,
                'product_id' => $product_id[$i],
                'product_discount' => $request->input('discount')[$i],
                'product_discount_type' => $request->input('discount_type')[$i],
            ];
            FlashProduct::insert($datasave);
        }

        return $result;
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
        $products = Product::select(['products.*'])->get();
        $flash_deal = FlashDeal::select(['flash_deals.*',\DB::raw('GROUP_CONCAT(flash_products.product_id) as f_products')])->where(['flash_deals.id'=>$id])
                    ->leftJoin('flash_products','flash_products.deals_id','=','flash_deals.id')
                    ->groupBy('flash_deals.id')
                    ->first();
        $flash_products = FlashProduct::select(['flash_products.*','products.product_name','products.taxable_price','products.thumbnail_img'])
                        ->leftjoin('products','products.id','=','flash_products.product_id')
                        ->where(['deals_id'=>$id])->get();
        return view('admin.flash-deals.edit',['products'=>$products,'flash_deal'=>$flash_deal,'flash_products'=>$flash_products]);
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
        $request->validate([
            'title'=>'required',
            'img'=>'image|mimes:jpeg,png,jpg|max:2048',
            'datetimes'=>'required',
            'flash_status'=>'required',
        ]);

        if($request->img != ''){
            $path = public_path().'/flash-deals/';
            //code for remove old file
            if($request->old_img != '' && $request->old_img != null){
                $file_old = $path.$request->old_img;
                if(file_exists($file_old)){
                    unlink($file_old);
                }
            }

            //upload new file
            $file = $request->img;
            $image = $request->img->getClientOriginalName();
            $file->move($path, $image); 
        }else{
            $image = $request->old_img;
        }

        if($request->slug != ''){
            $slug = str_replace(array('_',' ',),'-',strtolower($request->input('slug')));
        }else{
            $slug = str_replace(array('_',' ',),'-',strtolower($request->input('title')));
        }

        $flash = FlashDeal::where(['id'=>$id])->update([
            'flash_title'=>$request->input('title'),
            'flash_image'=>$image,
            'flash_date_range'=>$request->input('datetimes'),
            'flash_slug'=>$slug,
            'status'=>$request->input('flash_status'),
        ]);

        if(!empty($request->input('products'))){
            $product_id = $request->input('products');
            if($request->flash_id){
                $flash_id = $request->input('flash_id');
                DB::table('flash_products')->where('deals_id',$flash_id)->delete();
            }
            for($i=0; $i < count($product_id); $i++){
                $datasave = [
                    'deals_id' => $id,
                    'product_id' => $product_id[$i],
                    'product_discount' => $request->input('discount')[$i],
                    'product_discount_type' => $request->input('discount_type')[$i],
                ];
                FlashProduct::insert($datasave);
            }
        }else{
            if($request->flash_id){
                $flash_id = $request->input('flash_id');
                DB::table('flash_products')->where('deals_id',$flash_id)->delete();
            }
        }

        return $flash;

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
        $destroy = FlashDeal::where(['id'=>$id])->delete();
        FlashProduct::where(['deals_id'=>$id])->delete();
        return $destroy;
    }

    public function get_flash(Request $request){
        if($request->input()){
            $product_id = $request->flash;

            $products = Product::whereIn('id',$product_id)->get();

            $output = '';
            if(!empty($products)){
                foreach($products as $row){
                    $output .= '<tr id="prd'.$row->id.'">
                        <td>
                            <img src="'.asset("products/".$row->thumbnail_img).'" width="80px">
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="product_id" value="'.$row->id.'">
                            <span><b>Product Name :</b> '.$row->product_name.'</span><br>
                            <span><b>Product Price :</b> '.$row->taxable_price.'</span>
                        </td>
                        <td>
                            <span><b>Discount :</b></span>
                            <input type="number" class="form-control" name="discount[]" placeholder="Discount" value="0" required>
                        </td>
                        <td>
                            <span><b>Discount Type :</b></span>
                            <select class="form-control" name="discount_type[]" required>
                                <option value="flat" selected>Flat</option>
                                <option value="percent">Percent</option>
                            </select>
                        </td>
                    </tr>';
                }
            }else{
                $output .= '<option disabled selected value=">No Attribute Value Found</option>';
            }
            return $output;
        }
    }

    public function get_flash_edit(Request $request){
        if($request->input()){
            
            $product_id = $request->products;
           
            $flash_id = $request->flash;

            if(!empty($product_id)){
                $products = Product::whereIn('id',$product_id)->get();
            }else{
                $products = '';
            }

            // if(!empty($product_id)){
            //     $flash_products = FlashProduct::whereIn('product_id',$product_id)->where('deals_id',$flash_id)->get();
            // }else{
            //     $flash_products = '';
            // }
            
            $output = '';
            if(!empty($products)){
                foreach($products as $row){
                    $discount = '';
                    $flash_products = FlashProduct::where('product_id',$row->id)->where('deals_id',$flash_id)->first();
                    // if(isset($flash_products[$key])){
                        if(!empty($flash_products)){
                            $discount = $flash_products->product_discount;
                        }
                    // }
                    $output .= '<tr id="prd'.$row->id.'">
                        <td>
                            <img src="'.asset("products/".$row->thumbnail_img).'" width="80px">
                        </td>
                        <td>
                            <input type="hidden" class="form-control" name="product_id" value="'.$row->id.'">
                            <span><b>Product Name :</b> '.$row->product_name.'</span><br>
                            <span><b>Product Price :</b> '.$row->taxable_price.'</span>
                        </td>
                        <td>
                            <span><b>Discount :</b></span>
                            <input type="number" class="form-control" name="discount[]" value="'.$discount.'" placeholder="Discount">
                        </td>
                        <td>
                            <span><b>Discount Type :</b></span>
                            <select class="form-control" name="discount_type[]" id="">
                                <option value="flat">Flat</option>
                                <option value="percent">Percent</option>
                            </select>
                        </td>
                    </tr>';
                }
            }else{
                $output .= '<option disabled selected value=">No Products Found</option>';
            }
            return $output;
        }
    }
    
}
