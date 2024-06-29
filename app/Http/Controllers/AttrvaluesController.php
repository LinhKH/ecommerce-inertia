<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Attrvalue;
use Yajra\DataTables\DataTables;

class AttrvaluesController extends Controller
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
            $data = Attrvalue::select('attrvalues.*','attributes.title')
                                ->leftjoin('attributes','attrvalues.attribute','=','attributes.id')
                                ->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="attribute-values/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-attrvalue btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.attribute-values.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $attribute = Attribute::select('attributes.*')->get();
        return view('admin.attribute-values.create',['attribute'=>$attribute]);
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
            'attribute'=>'required',
            'value'=>['required',Rule::unique('attrvalues','value')->where(function($query) use ($request){ return $query->where('attribute', '=', $request->attribute);})],
        ]);

        $attrvalue = new Attrvalue();
        $attrvalue->attribute = $request->input('attribute');
        $attrvalue->value = $request->input('value');
        $result = $attrvalue->save();
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
        $attrvalue = Attrvalue::where(['id'=>$id])->first();
        $attribute = Attribute::select('*')->get();
        return view('admin.attribute-values.edit',['attrvalue'=>$attrvalue,'attribute'=>$attribute]);
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
            'attribute'=>'required',
            'value'=>['required',Rule::unique('attrvalues','value')->where(function($query) use ($request){ return $query->where('attribute', '=', $request->attribute)->where('id','!=',$request->attrvalue_id);})],
        ]);

        $attrvalue = Attrvalue::where(['id'=>$id])->update([
            'attribute'=>$request->input('attribute'),
            'value'=>$request->input('value'),
        ]);
        return $attrvalue;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Attrvalue::where(['id'=>$id])->delete();
        return $destroy;
    }
}
