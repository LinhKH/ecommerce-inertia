@extends('admin.layout')
@section('title','Reviews')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') All Reviews @endslot
        @slot('add_btn') @endslot
        @slot('active') All Reviews @endslot
    @endcomponent
    <!-- /.content-header -->

    <!-- show data table -->
    @component('admin.components.data-table',['thead'=>
        ['S No.','Product','User','Rating','Approved','Action']
    ])
        @slot('table_id') reviews_list @endslot
    @endcomponent


    <div class="modal view-review-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>sdfsdfsdfsdf</h4>
                    <p>sdfsadsadasdasdasd</p>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

@section('pageJsScripts')
<!-- DataTables -->
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/responsive.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    var table = $("#reviews_list").DataTable({
        processing: true,
        serverSide: true,
        ajax: "reviews",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'product_name', name: 'product_name'},
            {data: 'name', name: 'user_name'},
            {data: 'rating', name: 'rating'},
            {data: 'approved', name: 'approve'},
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