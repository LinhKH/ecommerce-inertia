<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
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
            $data = Category::select(['categories.*','t2.category_name as parent_name'])
            ->leftJoin('categories as t2','t2.id','=','categories.parent_category')
            ->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function($row){
                    if($row->category_icon != ''){
                        $img = '<img src="'.asset("category/".$row->category_icon).'" width="100px">';
                    }else{
                        $img = '';
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
                    $btn = '<a href="category/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-category btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::where('parent_category', 0)
                    ->with('childrenCategories')
                    ->get();
        $attributes = Attribute::get();
        return view('admin.category.create',['category'=>$category,'attributes'=>$attributes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $check_exist = Category::where('category_name',$request->name)->where('parent_category','0')->first();
        if($check_exist){
            return 'Category Name is Already Exists.';
        }
        if($request->img){
            $image = $request->img->getClientOriginalName();
            $request->img->move(public_path('category'), $image);
        }

        $slug = str_replace(array('_',' ',),'-',strtolower($request->input("name")));
        $check_slug = Category::where('category_slug',$slug)->first();
        if($check_slug){
            $n_slug = Category::where('id',$request->parent)->pluck('category_slug')->first();
            $slug = $n_slug.'-'.$slug;
        }

        $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input("name")));

        $category = new Category();
        if($request->img){
            $category->category_icon = $image;
        }
        if($request->order){
            $category->order = $request->order;
        }
        if ($request->parent != "0") {
            $category->parent_category = $request->parent;

            $parent = Category::find($request->parent);
            $category->level = $parent->level + 1 ;
        }
        $category->category_name = $request->name;
        $category->parent_category = $request->parent;
        if($request->meta_title != ''){
            $category->meta_title = $request->meta_title;
        }else{
            $category->meta_title = $meta_title;
        }
        $category->meta_desc = $request->meta_desc;
        $category->category_slug = $slug;
        if($request->cat_attributes){
            $category->filter_attr = implode(',',$request->input('cat_attributes'));
        }
        $result = $category->save();
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
        $category = Category::where(['id'=>$id])->first();
        $categories = Category::where('parent_category', 0)
        ->with('childrenCategories')
        ->get();
        $attributes = Attribute::get();
        return view('admin.category.edit',['category'=>$category,'categories'=>$categories,'attributes'=>$attributes]);
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
        // return $request->input();
        // $request->validate([
        //     'name'=>'required|unique:categories,category_name,' .$id. ',id',
        //     'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
        //     'category_status'=>'required'
        // ]);

        $check_exist = Category::where('category_name',$request->name)->where('parent_category','0')
        ->where('id','!=',$id)->first();
        if($check_exist){
            return 'Category Name is Already Exists.';
        }

        //update Brand Image
        if($request->img != ''){
            $path = public_path().'/category/';
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

        $check_slug = Category::where('category_slug',$slug)->where('id','!=',$id)->first();
        if($check_slug){
            $n_slug = Category::where('id',$request->parent)->pluck('category_slug')->first();
            $slug = $n_slug.'-'.$slug;
        }

        if($request->meta_title != ''){
            $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input('meta_title')));
        }else{
            $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input('name')));
        }

        $category = Category::FindOrFail($id);

        $category->category_name = $request->name;
        // $category->parent_category = $request->parent;

        $previous_level = $category->level;

        if($request->order){
            $category->order = $request->order;
        }
        if ($request->parent != "0") {
            $category->parent_category = $request->parent;

            $parent = Category::find($request->parent);
            $category->level = $parent->level + 1 ;
        }else{
            $category->parent_category = 0;
            $category->level = 0;
        }

        

        if($category->level > $previous_level){
            // return 'gr';
            $this->move_level_down($category->id);
        }
        elseif ($category->level < $previous_level) {
            // return 'lr';
            $this->move_level_up($category->id);
        }

        $category->meta_title = $meta_title;
        $category->meta_desc = $request->meta_desc;
        $category->category_slug = $slug;
        if(isset($request->cat_attributes)){
            $attr = $request->cat_attributes;
            $category->filter_attr = implode(',',$attr);
        }
        $category->status = $request->status;
        $update = $category->save();
        return $update;



        // $category = Category::where(['id'=>$id])->update([
        //     'category_name'=>$request->input('name'),
        //     'category_icon'=>$image,
        //     'meta_title'=>$meta_title,
        //     'meta_desc'=>$request->input('meta_desc'),
        //     'category_slug'=>$slug,
        //     'status'=>$request->input('category_status')
        // ]);
        return $update;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $child =Category::where('parent_category',$id)->count();
        $check =Product::where('category',$id)->count();
        // return $child;
        if($child == 0 && $check == 0){
            $destroy = Category::where(['id'=>$id])->delete();
            return $destroy;
        }else{
            return "You won't Delete this (This Category have children categories or used in Products.)";
        }
    }


    public function move_level_up($id){
        $children = Category::select('id')->where('parent_category', $id)->orderBy('order', 'desc')->pluck('id');
        if (count($children) > 0) {
            foreach ($children as $value) {
                $category = Category::find($value);
                $category->level -= 1;
                $category->save();
                $this->move_level_up($value);
            }
        }
    }

    public function move_level_down($id){
        $children = Category::select('id')->where('parent_category', $id)->orderBy('order', 'desc')->pluck('id');
        if (count($children) > 0) {
            foreach ($children as $value) {
                $category = Category::find($value);
                $category->level += 1;
                $category->save();
                $this->move_level_down($value);
            }
        }
    }
}
