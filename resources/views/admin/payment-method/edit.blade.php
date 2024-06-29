@extends('admin.layout')
@section('title','Edit Payment Method')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard','All Brand'=>'admin/payment-method']])
    @slot('title') Edit Payment Method @endslot
    @slot('add_btn')  @endslot
    @slot('active') Edit Payment Method @endslot
@endcomponent
<!-- Main content -->
<section class="content card">
    <div class="container-fluid card-body">
        <!-- form start -->
        <form class="form-horizontal" id="update_payment_method"  method="POST" enctype="multipart/form-data">
            @csrf
            {{ method_field('PUT') }}
            @if($paymentmethod)
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                   <input type="hidden" class="url" value="{{url('admin/payment-method/'.$paymentmethod->id)}}" >
                   <input type="hidden" class="rdt-url" value="{{url('admin/payment-method')}}" >
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Method Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Payment Name</span>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="payment_name" placeholder="Name" value="{{$paymentmethod->payment_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <span class="col-md-2">Image</span>
                                <div class="custom-file col-md-7">
                                    <input type="hidden" class="custom-file-input" name="old_img" value="{{$paymentmethod->payment_img}}" />
                                    <input type="file" class="custom-file-input" name="img" onChange="readURL(this);">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if($paymentmethod->payment_img != '')
                                    <img id="image" src="{{asset('/payment/'.$paymentmethod->payment_img)}}" alt="" width="150px">
                                    @else
                                    <img id="image" src="{{asset('/payment/default.png')}}" alt="" width="150px">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <span>Status</span>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="payment_status"  style="width: 100%;">
                                            <option value="publish" {{ ($paymentmethod->payment_status == "1" ? "selected":"") }}>Active</option>
                                            <option value="unublish" {{ ($paymentmethod->payment_status == "0" ? "selected":"") }}>Inactive</option>
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