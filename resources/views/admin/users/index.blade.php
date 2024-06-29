@extends('admin.layout')
@section('title','Users')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Users @endslot
        @slot('add_btn')  @endslot
        @slot('active') All Users @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
    ['S No.','Name','Email','Phone','City','Pin Code','State','Country','Action']
    ])
        @slot('table_id') user_list @endslot
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
    var table = $("#user_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "users",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'city_name', name: 'city'},
            {data: 'pin_code', name: 'pin_code'},
            {data: 'state_name', name: 'state'},
            {data: 'country_name', name: 'country'},
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