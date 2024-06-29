@extends('admin.layout')
@section('title', 'Users')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @component('admin.components.content-header', ['breadcrumb' => ['Dashboard' => 'admin/dashboard']])
            @slot('title')
                Product Sale Report
            @endslot
            @slot('add_btn')
            @endslot
            @slot('active')
                Product Sale Report
            @endslot
        @endcomponent
        <!-- /.content-header -->
        <div class="card mx-5">
            <div class="card-header">
                <span><b>Sort By Category</b></span>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card-body">
                        <select name="" class="form-control category-select" id="">
                            <option value="all" selected>All Products</option>
                            @foreach ($category as $list)
                                <option value="{{ $list->id }}">{{ $list->category_name }}</option>
                                @foreach ($list->childrenCategories as $childCategory)
                                    @include('admin.category.child_category', [
                                        'child_category' => $childCategory,
                                    ])
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- show data table -->
        @component('admin.components.data-table', ['thead' => ['S No.', 'Product Name', 'SALED (Đã Bán)']])
            @slot('table_id')
                sale_list
            @endslot
        @endcomponent
    </div>
@stop

@section('pageJsScripts')
    <!-- DataTables -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">
        var table = $("#sale_list").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "product-sale",
                data: function(d) {
                    d.category = $('.category-select option:selected').val();
                }
            },

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'total_id',
                    name: 'total_id'
                },

            ]
        });
        $(document).ready(function() {
            $('.category-select').change(function() {
                table.ajax.reload();
            })
        })
    </script>
@stop
