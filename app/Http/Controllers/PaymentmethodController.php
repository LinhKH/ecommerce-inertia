<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Yajra\DataTables\DataTables;

class PaymentmethodController extends Controller
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
            $data = PaymentMethod::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function($row){
                    if($row->payment_img != ''){
                        $img = '<img src="'.asset("images/".$row->payment_img).'" width="100px">';
                    }else{
                        $img = '<img src="'.asset("images/").'" width="100px">';
                    }
                    return $img;
                })
                ->editColumn('status', function($row){
                    if($row->payment_status == '0'){
                        $status = '<span class="badge badge-danger">Inactive</span>';
                    }else{
                        $status = '<span class="badge badge-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    if($row->payment_status == '1'){
                        $btn = '<button type="button" class="btn btn-danger btn-sm paymentStatus" data-status="0" data-id="'.$row->id.'">Inactive</button>';
                    }else{
                        $btn = '<button type="button" class="btn btn-success btn-sm paymentStatus" data-status="1" data-id="'.$row->id.'">Active</button>';
                    }
                    $btn .= ' <a href="payment-method/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-payment-method btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.payment-method.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.payment-method.create');
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
            'payment_name'=>'required|unique:paymentmethod,payment_name',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
            'payment_status'=>'required'
        ]);

        if($request->img){
            $image = $request->img->getClientOriginalName();
            $request->img->move(public_path('payment'), $image);
        }

        $paymentmethod = new PaymentMethod();
        if($request->img){
            $paymentmethod->payment_img = $image;
        }
        $paymentmethod->payment_name = $request->input('payment_name');
        $paymentmethod->payment_status = $request->input('payment_status');
        $result = $paymentmethod->save();
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
        $paymentmethod = PaymentMethod::where(['id'=>$id])->first();
        return view('admin.payment-method.edit',['paymentmethod'=>$paymentmethod]);
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
            'payment_name'=>'required|unique:paymentmethod,payment_name,'. $id . ',id',
            'img'=>'image|mimes:jpeg,jpg,png,svg|max:2048',
            'payment_status'=>'required'
        ]);

         //update Payment Image
         if($request->img != ''){
            $path = public_path().'/payment/';
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

        $paymentmethod = PaymentMethod::where(['id'=>$id])->update([
            'payment_name'=>$request->input('payment_name'),
            'payment_img'=>$image,
            'payment_status'=>$request->input('payment_status')
        ]);
        return $paymentmethod;
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
        $destroy = PaymentMethod::where(['id'=>$id])->delete();
        return $destroy;
    }

    public function changeStatus(Request $request){
        if($request->post()){
            $id = $request->post('payment_id');
            $payment_status = $request->post('payment_status');

            $paymentmethod = PaymentMethod::where('id',$id)->update([
                'payment_status' => $payment_status,
            ]);
            return $paymentmethod;
        }
    }

}
