@extends('admin.layout')
@section('title','Add New Banner Slider')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','Banner Slider'=>'admin/banner']])
    @slot('title') Add Banner Slider @endslot
    @slot('add_btn')  @endslot
    @slot('active') Add Banner Slider @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="add_banner"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/banner')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Banner Slider Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <span>Title</span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="title" placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <span class="col-md-2">Image </span>
                                <div class="custom-file col-md-7">
                                    <input type="file" class="custom-file-input" name="img" onChange="readURL(this);">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <div class="col-md-3 text-right">
                                    <img id="image" src="{{asset('/site/default.png')}}" alt=""  width="150px">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <span>Page Link</span>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="page_link" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <span>Status</span>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control" name="banner_status"  style="width: 100%;">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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
                    <button type="submit" class="btn btn-primary">Submit</button>
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