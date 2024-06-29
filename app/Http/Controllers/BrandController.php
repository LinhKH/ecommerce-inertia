<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
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
            $data = Brand::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function($row){
                    if($row->brand_img != ''){
                        $img = '<img src="'.asset("brand/".$row->brand_img).'" width="100px">';
                    }else{
                        $img = '<img src="'.asset("brand/").'" width="100px">';
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
                    $btn = '<a href="brand/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-brand btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $category = Category::where('parent_category', 0)
                    ->with('childrenCategories')
                    ->get();
        return view('admin.brand.create',['category'=>$category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->input();
        $request->validate([
            'name'=>'required|unique:brands,brand_name',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
            'brand_cat'=>'required',
            'brand_status'=>'required'
        ]);

        if($request->img){
            $image = $request->img->getClientOriginalName();
            $request->img->move(public_path('brand'), $image);
        }

        $slug = str_replace(array('_',' ',),'-',strtolower($request->input("name")));
        $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input("name")));

        $brand = new Brand();
        if($request->img){
            $brand->brand_img = $image;
        }
        $brand->brand_name = $request->input('name');
        $brand->brand_subcat = implode(',',$request->input('brand_cat'));
        if($request->input('meta_title') != ''){
            $brand->meta_title = $request->input('meta_title');
        }else{
            $brand->meta_title = $meta_title;
        }
        $brand->meta_desc = $request->input('meta_desc');
        $brand->brand_slug = $slug;
        $brand->status = $request->input('brand_status');
        $result = $brand->save();
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
        $brand = Brand::where(['id'=>$id])->first();
        $category = Category::where('parent_category', 0)
                    ->with('childrenCategories')
                    ->get();
        return view('admin.brand.edit',['brand'=>$brand,'category'=>$category]);
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
            'name'=>'required|unique:brands,brand_name,' .$id. ',id',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
            'brand_cat'=>'required',
            'brand_status'=>'required'
        ]);

        //update Brand Image
        if($request->img != ''){
            $path = public_path().'/brand/';
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
            $slug = str_replace(array('_',' ',),'-',strtolower($request->input('name')));
        }

        if($request->meta_title != ''){
            $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input('meta_title')));
        }else{
            $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input('name')));
        }

        $brand = Brand::where(['id'=>$id])->update([
            'brand_name'=>$request->input('name'),
            'brand_img'=>$image,
            'brand_subcat'=> implode(',',$request->input('brand_cat')),
            'meta_title'=>$meta_title,
            'meta_desc'=>$request->input('meta_desc'),
            'brand_slug'=>$slug,
            'status'=>$request->input('brand_status')
        ]);
        return $brand;
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
        $check = Product::where('brand',$id)->count();
        if($check == '0'){
            $destroy = Brand::where(['id'=>$id])->delete();
            return $destroy;
        }else{
            return "You don't delete this, This Brand is used in Products Table";
        }
    }
}
