@extends('admin.layout')
@section('title','Countries')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Countries @endslot
        @slot('add_btn') <a href="{{url('admin/countries/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Countries @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Name','Code','Status','Action']
    ])
        @slot('table_id') country_list @endslot
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
    var table = $("#country_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "countries",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'country_name', name: 'country_name'},
            {data: 'country_code', name: 'country_code'},
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