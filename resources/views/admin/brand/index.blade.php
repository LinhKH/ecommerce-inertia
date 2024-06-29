@extends('admin.layout')
@section('title','Brand')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Brands @endslot
        @slot('add_btn') <a href="{{url('admin/brand/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Brands @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Name','Status','Action']
    ])
        @slot('table_id') brand_list @endslot
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
    var table = $("#brand_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "brand",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'brand_name', name: 'brand_name'},
            {data: 'status', name: 'status'},
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