@extends('admin.layout')
@section('title','Edit City')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Cities'=>'admin/cities']])
    @slot('title') Edit City @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit City @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_city"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($city)
            <input type="hidden" name="city_id" value="{{$city->id}}">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/cities/'.$city->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/cities')}}" >
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">City Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{$city->city_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>State</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control select2" name="state" id="">
                                            @foreach($state as $item)
                                                @if($city->state == $item->id)
                                                    <option value="{{$item->id}}" selected>{{$item->state_name}}</option>
                                                @else
                                                    @if($item->status == '1')
                                                        <option value="{{$item->id}}">{{$item->state_name}}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Cost</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" name="cost" placeholder="Cost on this city" value="{{$city->cost_city}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="city_status"  style="width: 100%;">
                                            <option value="1" {{ ($city->status == "1" ? "selected":"") }}>Active</option>
                                            <option value="0" {{ ($city->status == "0" ? "selected":"") }}>Inactive</option>
                                        </select>
                                    </div>
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