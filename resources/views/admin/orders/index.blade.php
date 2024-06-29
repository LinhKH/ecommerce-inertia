@extends('admin.layout')
@section('title','Orders')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Orders @endslot
        @slot('add_btn')  @endslot
        @slot('active') All Orders @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['ORDER No.','Product Details','Total Amount','Customer Details','Order Date','Action']
    ])
        @slot('table_id') order_list @endslot
    @endcomponent

</div>
@stop

@section('pageJsScripts')
<!-- DataTables -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/responsive.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    var table = $("#order_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "orders",
        order: [0], //Initial no order.
        columns: [
            {data: 'order_id', name: 'order_id'},
            {data: 'p_id', name: 'product_id'},
            {data: 'amount', name: 'total_amount'},
            {data: 'user_details', name: 'user_details'},
            {data: 'created_at', name: 'order_date'},
            {
                data: 'action',
                name: 'action',
                orderable: true,
                searchable: true
            }
        ]
    });
</script>
@stop