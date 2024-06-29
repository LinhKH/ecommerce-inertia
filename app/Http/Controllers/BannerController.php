<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
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
            $data = Banner::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function($row){
                    if($row->banner_img != ''){
                        $img = '<img src="'.asset("banner/".$row->banner_img).'" width="100px">';
                    }else{
                        $img = '<img src="'.asset("banner/").'" width="100px">';
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
                    $btn = '<a href="banner/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-banner btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.banner.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.banner.create');
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
            'title'=>'required|unique:banner,title',
            'page_link'=>'required',
            'banner_status'=>'required',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
        ]);

        if($request->img){
            $image = $request->img->getClientOriginalName();
            $request->img->move(public_path('banner'), $image);
        }

        $banner = new Banner();
        if($request->img){
            $banner->banner_img = $image;
        }
        $banner->title = $request->input('title');
        $banner->pagelink = $request->input('page_link');
        $banner->status = $request->get('banner_status');
        $result = $banner->save();
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
        $banner = Banner::where(['id'=>$id])->first();
        return view('admin.banner.edit',['banner'=>$banner]);
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
            'page_link'=>'required',
            'banner_status'=>'required',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
        ]);

        //update banner image
        if($request->img != ''){
            $path = public_path().'/banner/';
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

        $banner = Banner::where(['id'=>$id])->update([
            'banner_img'=>$image,
            'title'=>$request->input('title'),
            'pagelink'=>$request->input('page_link'),
            'status'=>$request->input('banner_status'),
        ]);
        return $banner;
    }   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Banner::where(['id'=>$id])->delete();
        return $destroy;
    }
}
