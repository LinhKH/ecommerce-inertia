@extends('admin.layout')
@section('title','Pages')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Pages @endslot
        @slot('add_btn') <a href="{{url('admin/pages/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Pages @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Title','Show in header','Show in Footer','Status','Action']
    ])
        @slot('table_id') pages_list @endslot
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
    var table = $("#pages_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "pages",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'page_title', name: 'page_title'},
            {data: 'show_in_header', name: 'title'},
            {data: 'show_in_footer', name: 'title'},
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