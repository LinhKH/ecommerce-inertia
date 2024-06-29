<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use Yajra\DataTables\DataTables;

class CityController extends Controller
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
            $data = City::select('cities.*','states.state_name')
                                ->leftjoin('states','cities.state','=','states.id')
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
                    $btn = '<a href="cities/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-city btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $state = State::all();
        return view('admin.cities.create',['state'=>$state]);
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
            'name'=>['required',Rule::unique('cities','city_name')->where(function($query) use ($request){ return $query->where('state', '=', $request->state);})],
            'state'=>'required',
            'cost'=>'required',
            'city_status'=>'required'
        ]);

        $city = new City();
        $city->city_name = $request->input('name');
        $city->state = $request->input('state');
        $city->cost_city = $request->input('cost');
        $city->status = $request->input('city_status');
        $result = $city->save();
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
        $city = City::where(['id'=>$id])->first();
        $state = State::all();
        return view('admin.cities.edit',['city'=>$city,'state'=>$state]);
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
            'name'=>['required',Rule::unique('cities','city_name')->where(function($query) use ($request){ return $query->where('state', '=', $request->state)->where('id','!=',$request->city_id);})],
            'state'=>'required',
            'cost'=>'required',
            'city_status'=>'required'
        ]);

        $city = City::where(['id'=>$id])->update([
            'city_name'=>$request->input('name'),
            'state'=>$request->input('state'),
            'cost_city'=>$request->input('cost'),
            'status'=>$request->input('city_status'),
        ]);
        return $city;
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
        $destroy = City::where(['id'=>$id])->delete();
        return $destroy;
    }
}
