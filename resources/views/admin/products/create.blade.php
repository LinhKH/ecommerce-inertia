@extends('admin.layout')
@section('title', 'Add New Product')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @component('admin.components.content-header', [
            'breadcrumb' => ['Dashboard' => 'admin/dashboard', 'All Products' => 'admin/products'],
        ])
            @slot('title')
                Add Product
            @endslot
            @slot('add_btn')
            @endslot
            @slot('active')
                Add Product
            @endslot
        @endcomponent
        <!-- Main content -->
        <section class="content card">
            <div class="container-fluid card-body">
                <!-- form start -->
                <form class="form-horizontal" id="add_product" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-8">
                            <input type="hidden" class="url" value="{{ url('admin/products') }}">
                            <!-- jquery validation -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Product Information</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Product Name</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="product_name"
                                                    placeholder="Product Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Category</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="category" class="form-control">
                                                    <option value="" selected disabled>Select Category</option>
                                                    @foreach ($category as $list)
                                                        <option value="{{ $list->id }}">{{ $list->category_name }}
                                                        </option>
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
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Brand</span>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control select2" name="brand" id="">
                                                    <option value="" selected disabled>Select Brand</option>
                                                    @foreach ($brand as $item)
                                                        @if ($item->status == '1')
                                                            <option value="{{ $item->id }}">{{ $item->brand_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Unit</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="unit"
                                                    placeholder="Unit (eg.  KG, pc etc.)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Minimum Purchase Qty</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="min_qty" value="1"
                                                    placeholder="Minimum Purchase Qty">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Tags</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input id="tokenfield" type="text" class="form-control" name="tags"
                                                    placeholder="Type and hit enter to add a tag">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Barcode</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="barcode"
                                                    placeholder="Barcode">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Refundable</span>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkbox1" name="refundable">
                                                    <label for="checkbox1"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Images</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Gallery Images</span>
                                                <small>Images must be square in size (e.g. 800x800)</small>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="gallery-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Thumbnail Image</span>
                                                <small>Image must be square in size (e.g. 800x800)</small>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="file" class="custom-file-input" name="thumbnail_img"
                                                    onChange="readURL(this);">
                                                <label class="custom-file-label">Choose file</label>
                                            </div>
                                            <div class="col-md-2">
                                                <img id="image" src="{{ asset('products/default.png') }}"
                                                    alt="" width="100px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Variation</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Colors</span>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control select2" name="color[]" id=""
                                                    multiple="multiple">
                                                    <option value="" disabled>Select Colors</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table class="table">
                                            <thead>
                                                <th>Attribute</th>
                                                <th>Attribute value</th>
                                                <th><a href="javascript:;" class="btn btn-info addRow">+</a></th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Price</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Unit Price</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control unit-price" name="unit_price"
                                                    placeholder="Unit Price">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Tax</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control tax" name="tax" id="tax">
                                                    <option value="" disabled selected>Select Tax</option>
                                                    @foreach ($tax as $item)
                                                        @if ($item->status == '1')
                                                            <option value="{{ $item->id }}"
                                                                data-percent="{{ $item->percent }}">{{ $item->percent }}%
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group taxable_price">

                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Quantity</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="quantity"
                                                    placeholder="Quantity">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Discount Date Range</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="datefilter"
                                                    placeholder="Select Date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span>Discount</span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="number" class="form-control" name="discount"
                                                            placeholder="Discount" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" name="discount_type" id="">
                                                    <option value="flat">Flat</option>
                                                    <option value="percent">Percent</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>External Link</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="external_link"
                                                    placeholder="External Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>External Link button text</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="external_button"
                                                    placeholder="External Link button text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Description</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Description</span>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea name="description" id="summernote" class="form-control" id="" cols="30" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">SEO Meta Tags</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Meta Title</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="meta_title"
                                                    placeholder="Meta Title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Meta Description</span>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="meta_desc" placeholder="Meta Description" id="" cols="30"
                                                    rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Product Videos</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Video Provider</span>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control select2" name="video_provider"
                                                    id="">
                                                    <option value="">You Tube</option>
                                                    <option value="">Dailymotion</option>
                                                    <option value="">Vimeo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Video Link</span>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="video_link"
                                                    placeholder="Video Link">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Status</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span>Status</span>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control" name="product_status" style="width: 100%;">
                                                    <option value="1" selected>Published</option>
                                                    <option value="0">Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Stock Visibility Store</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <span>Show Stock Quantity</span>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkbox2" name="show_qty">
                                                    <label for="checkbox2"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Today Deal</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span>Status</span>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkbox5" name="today_deal">
                                                    <label for="checkbox5"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Shipping Configuration</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span>Shipping Charges</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control" name="shipping_charges" id="">
                                                    <option value="" disabled selected>Select Shipping Charges
                                                    </option>
                                                    <option value="free">Free Shipping</option>
                                                    <option value="area">Area Wise</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <span>Shipping Days</span> <small class="text-danger">*</small>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="number" class="form-control" name="shipping_days"
                                                    placeholder="Shipping Days">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-12">
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </form> <!-- /.form start -->
            </div><!-- /.container-fluid -->
        </section><!-- /.content -->
    </div>
@stop
@section('pageJsScripts')
    <script src="{{ asset('assets/js/Taginput.js') }}"></script>
    <script src="{{ asset('assets/js/tokenfield.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).on('change', '.tax', function() {
            var tax_rate = $(this).children('option:selected').data('percent');
            var unit_price = $('input[name="unit_price"]').val();
            var tax_total = unit_price * tax_rate / 100;
            var total = parseInt(unit_price) + parseInt(tax_total);

            var row = '<div class="row">' +
                '<div class="col-md-3">' +
                '<span>Taxable Price</span>' +
                '</div>' +
                '<div class="col-md-9">' +
                '<input type="number" class="form-control" name="taxable_price" placeholder="Taxable Price" value="' +
                total + '" disabled>' +
                '</div>' +
                '</div>';
            $('.taxable_price').html(row);
        });

        var count = 0;
        $('thead').on('click', '.addRow', function() {
            count++;
            var tr =    '<tr>' +
                            '<td>' +
                                '<select name="attribute[]" id="attribute" class="form-control attribute-select" data-attr_value="' + count + '">' +
                                    '<option value="">Select an Attribute</option>' +
                                    <?php foreach($attribute as $item){ ?> '<option value="{{ $item->id }}" data-attribute="{{ $item->id }}">{{ $item->title }}</option>' +
                                <?php }?> '</select>' +
                            '</td>' +
                            '<td>' +
                                '<select class="form-control attrvalue-select select2" name="attrvalue' + count + '[]" id="attrvalue' + count + '" data-attr_values="' + count + '" multiple>' +

                                '</select>' +
                            '</td>' +
                            '<td><a href="javascript:;" class="btn btn-danger deleteRow">-</a></td>' +
                        '</tr>';

            $('tbody').append(tr);
            $('.select2').select2();
        });

        $('tbody').on('click', '.deleteRow', function() {
            $(this).parent().parent().remove();
        });

        $('.gallery-images').imageUploader({
            imagesInputName: 'gallery',
            'label': 'Drag and Drop'
        });

        $('#form-tags-1').tagsInput();

        $('#tokenfield').tokenfield({
            autocomplete: {
                delay: 100
            },
            showAutocompleteOnFocus: true
        });
    </script>
@stop
