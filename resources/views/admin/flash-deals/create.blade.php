@extends('admin.layout')
@section('title','Add New Flash Deals')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','Flash Deals'=>'admin/flash-deals']])
    @slot('title') Add Flash Deals @endslot
    @slot('add_btn')  @endslot
    @slot('active') Add Flash Deals @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="add_flash_deal"  method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/flash-deals')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Flash Deals Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Title</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title" placeholder="Title">
                                    </div>
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
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Discount Date Range</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="datetimes" placeholder="Select Date">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Products</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control addRow select2" name="products[]" id="products" multiple="multiple">
                                            @php $count = 0; @endphp
                                            @foreach($products as $product)
                                                @php $count++; @endphp
                                                <option value="{{$product->id}}" data-flash="{{$product->id}}" data-count={{$count}}>{{$product->product_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="selected-products">
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="flash_status">
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form> <!-- /.form start -->
    </div><!-- /.container-fluid -->
</section><!-- /.content -->
</div>
@stop
@section('pageJsScripts')
<script src="{{asset('assets/js/Taginput.js')}}"></script>
<script src="{{asset('assets/js/tokenfield.js')}}"></script>
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

    $(document).on('change', '.addRow', function(){
        var data = '<table class="table table-bordered mt-3">'+
            '<tbody id="flash">'+
                
            '</tbody>'+
        '</table>';


        $('.selected-products').html(data);
    });

</script>
@stop