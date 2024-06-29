<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Review::select('reviews.*','products.product_name','users.name')
                                ->leftjoin('products','products.id','=','reviews.product')
                                ->leftjoin('users','users.user_id','=','reviews.user')
                                ->orderBy('reviews.id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('approved', function($row){
                    if($row->approved == '1'){
                        $status = '<span class="badge badge-info">Approved</span>';
                    }else{
                        $status = '<button class="btn btn-success btn-sm approve-review" data-id="'.$row->id.'">Approve</button>';
                    }
                    if($row->hide_by_admin == '1'){
                        $status .= '<small class="text-danger d-block">Hidden by admin</small>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '<button class="btn btn-info btn-sm view-review" data-id="'.$row->id.'"><i class="fa fa-eye"></i></button> <a href="reviews/'.$row->id.'/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-review btn btn-danger btn-sm" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['approved','status','action'])
                ->make(true);
        }
        return view('admin.reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        

        if (Session::has('user_id')) {
            $product = Product::where('id', $id)->first();
            
            return Inertia::render('Reviews', ['product' => $product]);
        } else {
            return Inertia::render('UserLogin');
        }

        // return view('public.reviews.create-review',['product'=>$product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'review' => 'required',
            'star' => 'required',
        ]);
        $insert = new Review();
        $insert->product = $request->product;
        $insert->user = $request->user;
        $insert->title = $request->title;
        $insert->desc = $request->review;
        $insert->rating = $request->star;
        $save = $insert->save();
        if ($save) {
            return redirect('my-reviews');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $review = Review::select(['reviews.*','products.product_name','users.name'])
                    ->leftJoin('products','products.id','=','reviews.product')
                    ->leftJoin('users','users.user_id','=','reviews.user')
                    ->where('reviews.id',$request->view)
                    ->first();
        return view('admin.reviews.view',['review'=>$review]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = Review::select(['reviews.*','products.product_name','users.name'])
                    ->leftJoin('products','products.id','=','reviews.product')
                    ->leftJoin('users','users.user_id','=','reviews.user')
                    ->where('reviews.id',$id)
                    ->first();
        return view('admin.reviews.edit',['review'=>$review]);
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
        // $request->validate([
        //     't'=>'required|unique:states,state_name,' .$id. ',id',
        //     'country'=>'required',
        //     'state_status'=>'required'
        // ]);

        $review = Review::where(['id'=>$id])->update([
            'title'=>$request->input('title'),
            'desc'=>$request->input('desc'),
            'hide_by_admin'=>$request->input('status'),
        ]);
        return $review;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   
        $destroy = Review::where(['id'=>$request->delete])->delete();
        return $destroy;
    }


    public function approveReview(Request $request){
        $id = $request->approve;
        $update = Review::where('id',$id)
                            ->update([
                                'approved'=>'1'
                            ]);
        return $update;
    }
}
