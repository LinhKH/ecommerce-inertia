@extends('admin.layout')
@section('title','Color')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Colors @endslot
        @slot('add_btn') <a href="{{url('admin/colors/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Colors @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Name','Color Code','Action']
    ])
        @slot('table_id') color_list @endslot
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
    var table = $("#color_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "colors",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'color_name', name: 'color_name'},
            {data: 'color_code', name: 'color_code'},
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