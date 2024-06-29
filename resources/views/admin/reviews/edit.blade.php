@extends('admin.layout')
@section('title','Edit Review')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Reviews'=>'admin/reviews']])
    @slot('title') Edit Review @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Review @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_review"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($review)
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Review</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-md-2">Product </div>
                                <div class="col-md-10">{{$review->product_name}}</div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">User </div>
                                <div class="col-md-10">{{$review->name}}</div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Title</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{$review->title}}" required>
                                        <input type="text" class="id" value="{{$review->id}}" hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Description</span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea name="desc" class="form-control" required>{{$review->desc}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">Rating </div>
                                <div class="col-md-10">{{$review->rating}}</div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2">Status </div>
                                <div class="col-md-10">
                                    <select name="status" class="form-control">
                                        <option value="1" @if($review->hide_by_admin == '1') selected @endif >Hide</option>
                                        <option value="0" @if($review->hide_by_admin == '0') selected @endif >Show</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            @endif
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form> <!-- /.form start -->
    </div><!-- /.container-fluid -->
</section><!-- /.content -->
</div>

@stop