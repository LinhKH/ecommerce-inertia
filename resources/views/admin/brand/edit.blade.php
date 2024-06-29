@extends('admin.layout')
@section('title','Edit Brand')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Brand'=>'admin/brand']])
    @slot('title') Edit Brand @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Brand @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_brand"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($brand)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/brand/'.$brand->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/brand')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Brand Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{$brand->brand_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <span class="col-md-2">Image</span>
                                <div class="custom-file col-md-7">
                                    <input type="hidden" class="custom-file-input" name="old_img" value="{{$brand->brand_img}}" />
                                    <input type="file" class="custom-file-input" name="img" onChange="readURL(this);">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if($brand->brand_img != '')
                                    <img id="image" src="{{asset('/brand/'.$brand->brand_img)}}" alt="" width="150px">
                                    @else
                                    <img id="image" src="{{asset('/brand/default.png')}}" alt="" width="150px">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Category</span>
                                    </div>
                                    @php $brand_cat = array_filter(explode(',',$brand->brand_subcat));  @endphp
                                    <div class="col-md-10">
                                        <select class="form-control select2" name="brand_cat[]" multiple="multiple">
                                            @foreach($category as $list)
                                                @php $selected = (in_array($list->id,$brand_cat)) ? 'selected' : '';  @endphp
                                                <option value="{{$list->id}}" {{$selected}}>{{$list->category_name}}</option>
                                                @foreach ($list->childrenCategories as $childCategory)
                                                    @include('admin.brand.edit_category', ['child_category' => $childCategory])
                                                @endforeach
                                            @endforeach
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
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="{{$brand->meta_title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Description</span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="meta_desc" placeholder="Meta Description" id="" cols="30" rows="3">{{$brand->meta_desc}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Slug</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{$brand->brand_slug}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="brand_status"  style="width: 100%;">
                                            <option value="1" {{ ($brand->status == "1" ? "selected":"") }}>Active</option>
                                            <option value="0" {{ ($brand->status == "0" ? "selected":"") }}>Inactive</option>
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