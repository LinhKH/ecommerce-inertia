<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
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
            $data = Country::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function($row){
                    if($row->status == '1'){
                        $status = '<span class="badge badge-success">Active</span>';
                    }else{
                        $status = '<span class="badge badge-danger">Inactive</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="countries/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-country btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.countries.create');
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
            'name'=>'required|unique:countries,country_name',
            'code'=>'required',
            'country_status'=>'required'
        ]);

        $country = new Country();
        $country->country_name = $request->input('name');
        $country->country_code = $request->input('code');
        $country->status = $request->input('country_status');
        $result = $country->save();
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
        $country = Country::where(['id'=>$id])->first();
        return view('admin.countries.edit',['country'=>$country]);
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
            'name'=>'required|unique:countries,country_name,' .$id. ',id',
            'code'=>'required',
            'country_status'=>'required'
        ]);

        $country = Country::where(['id'=>$id])->update([
            'country_name'=>$request->input('name'),
            'country_code'=>$request->input('code'),
            'status'=>$request->input('country_status'),
        ]);
        return $country;
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
        $destroy = Country::where(['id'=>$id])->delete();
        return $destroy;
    }
}
