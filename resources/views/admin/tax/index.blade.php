@extends('admin.layout')
@section('title','Tax')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Tax @endslot
        @slot('add_btn') <a href="{{url('admin/tax/create')}}" class="align-top btn btn-sm btn-primary">Add New</a> @endslot
        @slot('active') All Tax @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Percent','Status','Action']
    ])
        @slot('table_id') tax_list @endslot
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
    var table = $("#tax_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "tax",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'percent', name: 'percent'},
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