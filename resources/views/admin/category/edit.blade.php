@extends('admin.layout')
@section('title','Edit Category')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Category'=>'admin/category']])
    @slot('title') Edit Category @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Category @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_category"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($category)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/category/'.$category->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/category')}}" >
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
                                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{$category->category_name}}">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <span class="col-md-2">Image</span>
                                <div class="custom-file col-md-7">
                                    <input type="hidden" class="custom-file-input" name="old_img" value="{{$category->category_icon}}" />
                                    <input type="file" class="custom-file-input" name="img" onChange="readURL(this);">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if($category->category_icon != '')
                                    <img id="image" src="{{asset('/category/'.$category->category_icon)}}" alt="" width="150px">
                                    @else
                                    <img id="image" src="{{asset('/category/default.png')}}" alt="" width="150px">
                                    @endif
                                </div>
                            </div> -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Parent Category</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="parent" class="form-control select2">
                                            <option value="0" {{($category->parent_category == '0') ? 'selected' : ''}}>No Parent</option>
                                            @foreach($categories as $list)
                                                @if($list->id != $category->id)
                                                <option value="{{$list->id}}" {{($category->parent_category == $list->id) ? 'selected' : ''}}>{{$list->category_name}}</option>
                                                @foreach ($list->childrenCategories as $childCategory)
                                                    @if($childCategory->id != $category->id)
                                                    @include('admin.category.edit_child_category', ['child_category' => $childCategory])
                                                    @endif
                                                @endforeach
                                                @endif
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
                                        <input type="number" class="form-control" name="order" value="{{$category->order}}">
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
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="{{$category->meta_title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Meta Description</span>
                                    </div>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="meta_desc" placeholder="Meta Description" id="" cols="30" rows="3">{{$category->meta_desc}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Slug</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{$category->category_slug}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Filter Attributes</span>
                                    </div>
                                    <div class="col-md-10">
                                        @php $cat_attr = array_filter(explode(',',$category->filter_attr));  
                                        @endphp
                                        <select name="cat_attributes[]" class="form-control select2" multiple="multiple">
                                            @foreach($attributes as $attr)
                                            @php $selected = (in_array($attr->id,$cat_attr)) ? 'selected' : '';   @endphp
                                                <option value="{{$attr->id}}" {{$selected}}>{{$attr->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="status">
                                            <option value="1" {{ ($category->status == "1" ? "selected":"") }}>Active</option>
                                            <option value="0" {{ ($category->status == "0" ? "selected":"") }}>Inactive</option>
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