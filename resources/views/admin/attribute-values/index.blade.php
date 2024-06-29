@extends('admin.layout')
@section('title','Attribute Values')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Attribute Values @endslot
        @slot('add_btn') <a href="{{url('admin/attribute-values/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Attribute Values @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Values','Attribute','Action']
    ])
        @slot('table_id') attribute_values_list @endslot
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
    var table = $("#attribute_values_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "attribute-values",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'value', name: 'value'},
            {data: 'title', name: 'title'},
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