<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use Yajra\DataTables\DataTables;

class StateController extends Controller
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
            $data = State::select('states.*','countries.country_name')
                                ->leftjoin('countries','states.country','=','countries.id')
                                ->orderBy('id','desc')->get();
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
                    $btn = '<a href="states/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-state btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.states.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $country = Country::all();
        return view('admin.states.create',['country'=>$country]);
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
            'name'=>'required|unique:states,state_name',
            'country'=>'required',
            'state_status'=>'required'
        ]);

        $state = new State();
        $state->state_name = $request->input('name');
        $state->country = $request->input('country');
        $state->status = $request->input('state_status');
        $result = $state->save();
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
        $state = State::where(['id'=>$id])->first();
        $country = Country::all();
        return view('admin.states.edit',['state'=>$state,'country'=>$country]);
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
            'name'=>'required|unique:states,state_name,' .$id. ',id',
            'country'=>'required',
            'state_status'=>'required'
        ]);

        $state = State::where(['id'=>$id])->update([
            'state_name'=>$request->input('name'),
            'country'=>$request->input('country'),
            'status'=>$request->input('state_status'),
        ]);
        return $state;
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
        $destroy = State::where(['id'=>$id])->delete();
        return $destroy;
    }
}
