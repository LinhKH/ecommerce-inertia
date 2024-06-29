@extends('admin.layout')
@section('title','States')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All States @endslot
        @slot('add_btn') <a href="{{url('admin/states/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All States @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Name','Country','Status','Action']
    ])
        @slot('table_id') state_list @endslot
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
    var table = $("#state_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "states",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'state_name', name: 'state_name'},
            {data: 'country_name', name: 'country_name'},
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