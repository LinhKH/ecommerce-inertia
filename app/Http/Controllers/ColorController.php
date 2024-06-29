<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class ColorController extends Controller
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
            $data = Color::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="colors/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-color btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.colors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.colors.create');
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
            'color_name'=>'required|unique:colors,color_name',
            'color_code'=>'required'
        ]);

        $color = new Color();
        $color->color_name = $request->input('color_name');
        $color->color_code = $request->input('color_code');
        $result = $color->save();
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
        $color = Color::where(['id'=>$id])->first();
        return view('admin.colors.edit',['color'=>$color]);
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
            'color_name'=>'required|unique:colors,color_name,' .$id. ',id',
            'color_code'=>'required'
        ]);

        $color = Color::where(['id'=>$id])->update([
            'color_name'=>$request->input('color_name'),
            'color_code'=>$request->input('color_code'),
        ]);
        return $color;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = Product::where('colors','LIKE',"%{$id}%")->count();
        if($check == '0'){
            $destroy = Color::where(['id'=>$id])->delete();
            return $destroy;
        }else{
            return "You don't delete this, This color is used in Products Table";
        }
        
    }
}
