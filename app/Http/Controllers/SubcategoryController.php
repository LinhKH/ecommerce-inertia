<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use Yajra\DataTables\DataTables;

class SubcategoryController extends Controller
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
            $data = Subcategory::select('subcategories.*','categories.category_name')
                                ->leftjoin('categories','subcategories.parent_category','=','categories.id')
                                ->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function($row){
                    if($row->status == '1'){
                        $status = '<span class="badge badge-success">Publish</span>';
                    }else{
                        $status = '<span class="badge badge-danger">Unpublish</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="sub-category/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-subcategory btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.sub-category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $category = Category::all();
        return view('admin.sub-category.create',['category'=>$category]);
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
            'name'=>'required|unique:subcategories,subcat_name',
            'parent_cat'=>'required',
            'subcategory_status'=>'required'
        ]);

        $slug = str_replace(array('_',' ',),'-',strtolower($request->input("name")));
        $meta_title = str_replace(array('_',' ',),'-',strtolower($request->input("name")));

        $subcategory = new Subcategory();
        $subcategory->subcat_name = $request->input('name');
        $subcategory->parent_category = $request->input('parent_cat');
        if($request->input('meta_title') != ''){
            $subcategory->meta_title = $request->input('meta_title');
        }else{
            $subcategory->meta_title = $meta_title;
        }
        $subcategory->meta_desc = $request->input('meta_desc');
        $subcategory->subcat_slug = $slug;
        $subcategory->status = $request->input('subcategory_status');
        $result = $subcategory->save();
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
        $subcategory = Subcategory::where(['id'=>$id])->first();
        $category = Category::all();
        return view('admin.sub-category.edit',['subcategory'=>$subcategory,'category'=>$category]);
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
            'name'=>'required|unique:subcategories,subcat_name,' .$id. ',id',
            'parent_cat'=>'required',
            'subcategory_status'=>'required'
        ]);


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

        $subcategory = Subcategory::where(['id'=>$id])->update([
            'subcat_name'=>$request->input('name'),
            'parent_category'=>$request->input('parent_cat'),
            'meta_title'=>$meta_title,
            'meta_desc'=>$request->input('meta_desc'),
            'subcat_slug'=>$slug,
            'status'=>$request->input('subcategory_status')
        ]);
        return $subcategory;
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
        $destroy = Subcategory::where(['id'=>$id])->delete();
        return $destroy;
    }
}
