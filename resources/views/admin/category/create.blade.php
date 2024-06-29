@extends('admin.layout')
@section('title','Add New Category')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Category'=>'admin/category']])
    @slot('title') Add Category @endslot
    @slot('add_btn')  @endslot
    @slot('active') Add Category @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="add_category"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/category')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Category Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Parent Category</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="parent" class="form-control">
                                            <option value="0" selected>No Parent</option>
                                            @foreach($category as $list) 
                                                <option value="{{$list->id}}">{{$list->category_name}}</option>
                                                @foreach ($list->childrenCategories as $childCategory)
                                                    @include('admin.category.child_category', ['child_category' => $childCategory])
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Ordering Number</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control" name="order">
                                        <small>Higher Number has high priority</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Title</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Description</span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="meta_desc" placeholder="Meta Description" id="" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Filter Attributes</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="cat_attributes[]" class="form-control select2" multiple="multiple">
                                            @foreach($attributes as $attr)
                                                <option value="{{$list->id}}">{{$attr->title}}</option>
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
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
                <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form> <!-- /.form start -->
    </div><!-- /.container-fluid -->
</section><!-- /.content -->
</div>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
</script>
@stop