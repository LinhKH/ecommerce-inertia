@extends('admin.layout')
@section('title','Edit Attribute Values')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Attributes Values'=>'admin/attribute-values']])
    @slot('title') Edit Attribute Values @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Attribute Values @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_attr_value"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($attrvalue)
            <input type="hidden" name="attrvalue_id" value="{{$attrvalue->id}}">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/attribute-values/'.$attrvalue->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/attribute-values')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Attribute Values Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Value</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="value" placeholder="Value" value="{{$attrvalue->value}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Attribute</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="attribute" id="">
                                            @foreach($attribute as $item)
                                                @if($attrvalue->attribute == $item->id)
                                                    <option value="{{$item->id}}" selected>{{$item->title}}</option>
                                                @else
                                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                                @endif
                                            @endforeach
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