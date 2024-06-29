@extends('admin.layout')
@section('title','Edit Sub Category')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Sub Category'=>'admin/sub-category']])
    @slot('title') Edit Sub Category @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Sub Category @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_subcategory"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($subcategory)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/sub-category/'.$subcategory->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/sub-category')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sub Category Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{$subcategory->subcat_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Parent Category</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control select2" name="parent_cat" id="">
                                            @if(!empty($category))
                                                @foreach($category as $item)
                                                    @if($subcategory->parent_category == $item->id)
                                                        <option value="{{$item->id}}" selected>{{$item->category_name}}</option>
                                                    @else
                                                        @if($item->status == "publish")
                                                            <option value="{{$item->id}}">{{$item->category_name}}</option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Title</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="{{$subcategory->meta_title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Description</span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="meta_desc" placeholder="Meta Description" id="" cols="30" rows="3">{{$subcategory->meta_desc}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Slug</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{$subcategory->subcat_slug}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="subcategory_status"  style="width: 100%;">
                                            <option value="1" {{ ($subcategory->status == "1" ? "selected":"") }}>Publish</option>
                                            <option value="0" {{ ($subcategory->status == "0" ? "selected":"") }}>Unpublish</option>
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