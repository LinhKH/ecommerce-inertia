@extends('admin.layout')
@section('title','Social Links')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
    @slot('title') Social Links @endslot
    @slot('add_btn')  @endslot
    @slot('active') Social Links @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_social"  method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            @foreach($social as $item)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Social Links Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label>Instagram</label>
                                <input type="url" class="form-control" name="instagram" placeholder="Enter Instagram Url" value="{{$item->instagram}}">
                                <small>Leave this field empty if you want to hide this icon</small>
                            </div>
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="url" class="form-control" name="twitter" placeholder="Enter Twitter Url" value="{{$item->twitter}}">
                                <small>Leave this field empty if you want to hide this icon</small>
                            </div>
                            <div class="form-group">
                                <label>Facebook</label>
                                <input type="url" class="form-control" name="facebook" placeholder="Enter Facebook Url" value="{{$item->facebook}}">
                                <small>Leave this field empty if you want to hide this icon</small>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            @endforeach
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