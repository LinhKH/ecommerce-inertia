@extends('admin.layout')
@section('title','Edit Flash Deals')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','Flash Deals'=>'admin/flash-deals']])
    @slot('title') Edit Flash Deals @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Flash Deals @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_flash_deal"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($flash_deal)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <input type="hidden" class="url" value="{{url('admin/flash-deals/'.$flash_deal->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/flash-deals')}}" >

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
                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{$flash_deal->flash_title}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <span class="col-md-2">Image </span>
                                <div class="custom-file col-md-7">
                                    <input type="hidden" class="custom-file-input" name="old_img" value="{{$flash_deal->flash_image}}" />
                                    <input type="file" class="custom-file-input" name="img" onChange="readURL(this);">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if($flash_deal->flash_image != '')
                                        <img id="image" src="{{asset('/flash-deals/'.$flash_deal->flash_image)}}" alt=""  width="100px">
                                    @else
                                        <img id="image" src="{{asset('/flash-deals/default.png')}}" alt=""  width="100px">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Discount Date Range</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="datetimes" placeholder="Select Date" value="{{$flash_deal->flash_date_range}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Products</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control addRow select2" name="products[]" id="editProducts" multiple="multiple">
                                        @php $old = '';  @endphp
                                        @foreach($products as $row)
                                            @php 
                                                $f_products = array_filter(explode(',',$flash_deal->f_products));
                                                $selected = (in_array($row->id,$f_products)) ? 'selected' : '';
                                            @endphp
                                            <option value="{{$row->id}}" {{$selected}}>{{$row->product_name}}</option>
                                        @endforeach
                                        </select>
                                        <input type="hidden" name="old_products" value="{{$flash_deal->f_products}}">
                                        <input type="hidden" name="prd_id[]" value="{{$flash_deal->f_products}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="selected-products">
                                            <table class="table table-bordered mt-3">
                                                <tbody id="flash">
                                                    @foreach($flash_products as $item)
                                                    <input type="hidden" name="flash_id" value="{{$item->deals_id}}" >
                                                    <tr id="prd{{$item->product_id}}">
                                                        <td><img id="image" src="{{asset('/products/'.$item->thumbnail_img)}}" alt=""  width="100px"></td>
                                                        <td>
                                                            <span><b>Product Name :</b> {{$item->product_name}}</span><br>
                                                            <span><b>Product Price :</b> {{$item->taxable_price}}</span>
                                                        </td>
                                                        <td>
                                                            <span><b>Discount :</b></span>
                                                            <input type="number" class="form-control" name="discount[]" placeholder="Discount" value="{{$item->product_discount}}">
                                                        </td>
                                                        <td>
                                                            <span><b>Discount Type :</b></span>
                                                            <select class="form-control" name="discount_type[]" id="">
                                                                <option value="flat" {{ ($item->product_discount_type == "flat" ? "selected":"") }}>Flat</option>
                                                                <option value="percent" {{ ($item->product_discount_type == "percent" ? "selected":"") }}>Percent</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Slug</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{$flash_deal->flash_slug}}">
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="flash_status"  style="width: 100%;">
                                            <option value="1" {{ ($flash_deal->status == "1" ? "selected":"") }}>Active</option>
                                            <option value="0" {{ ($flash_deal->status == "0" ? "selected":"") }}>Inactive</option>
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
        if ($('.selected-products').is(':empty')){
            $('.selected-products').append(data);
        }
    });

    // $(document).on("change", '#products', function(){
    //     $(this).remove();
    // });

</script>
@stop