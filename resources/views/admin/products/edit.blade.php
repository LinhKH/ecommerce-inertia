@extends('admin.layout')
@section('title', 'Edit Product')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @component('admin.components.content-header', [
            'breadcrumb' => ['Dashboard' => 'admin/dashboard', 'All Products' => 'admin/products'],
        ])
            @slot('title')
                Edit Product
            @endslot
            @slot('add_btn')
            @endslot
            @slot('active')
                Edit Product
            @endslot
        @endcomponent
        <!-- Main content -->
        <section class="content card">
            <div class="container-fluid card-body">
                <!-- form start -->
                <form class="form-horizontal" id="update_product" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    @if ($products)
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-8">
                                <input type="hidden" class="url" value="{{ url('admin/products/' . $products->id) }}">
                                <input type="hidden" class="rdt-url" value="{{ url('admin/products') }}">
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
                                                        placeholder="Product Name" value="{{ $products->product_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Category</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <select name="category" class="form-control select2">
                                                        @foreach ($category as $list)
                                                            <option value="{{ $list->id }}"
                                                                {{ $products->category == $list->id ? 'selected' : '' }}>
                                                                {{ $list->category_name }}</option>
                                                            @foreach ($list->childrenCategories as $childCategory)
                                                                @include(
                                                                    'admin.products.product_edit_child_category',
                                                                    ['child_category' => $childCategory]
                                                                )
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
                                                            @if ($products->brand == $item->id)
                                                                <option value="{{ $item->id }}" selected>
                                                                    {{ $item->brand_name }}</option>
                                                            @else
                                                                @if ($item->status == '1')
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->brand_name }}</option>
                                                                @endif
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
                                                        placeholder="Unit" value="{{ $products->unit }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Minimum Purchase Qty</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="min_qty"
                                                        placeholder="Minimum Purchase Qty"
                                                        value="{{ $products->min_qty }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Tags</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <input id="tokenfield" type="text" class="form-control"
                                                        name="tags" placeholder="Type and hit enter to add a tag"
                                                        value="{{ $products->tags }}">
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
                                                        placeholder="Barcode" value="{{ $products->barcode }}">
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
                                                        <input type="checkbox" id="checkbox1" name="refundable"
                                                            {{ $products->refundable == '1' ? 'checked' : '' }}>
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
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="gallery-images1"></div>
                                                    <input type="text" hidden name="old_gallery"
                                                        value="{{ $products->gallery_img }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Thumbnail Image</span>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="hidden" class="custom-file-input" name="old_img"
                                                        value="{{ $products->thumbnail_img }}" />
                                                    <input type="file" class="custom-file-input" name="thumbnail_img"
                                                        onChange="readURL(this);">
                                                    <label class="custom-file-label">Choose file</label>
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($products->thumbnail_img != '')
                                                        <img id="image"
                                                            src="{{ asset('products/' . $products->thumbnail_img) }}"
                                                            alt="" width="100px">
                                                    @else
                                                        <img id="image" src="{{ asset('products/default.png') }}"
                                                            alt="" width="100px">
                                                    @endif
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
                                                        @if (!empty($colors))
                                                            @php
                                                                $row_facility = array_filter(
                                                                    explode(',', $products->colors),
                                                                );
                                                            @endphp
                                                            @foreach ($colors as $item)
                                                                @if (in_array($item->id, $row_facility))
                                                                    <option value="{{ $item->id }}" selected>
                                                                        {{ $item->color_name }}</option>
                                                                @else
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->color_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
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
                                                    @php $count = 0; @endphp
                                                    @foreach ($attribute_values as $value)
                                                        @php $count++; @endphp
                                                        <input type="hidden" name="attr_id[]"
                                                            value="{{ $value->id }}">
                                                        <input type="hidden" name="attribute_id"
                                                            value="{{ $value->attribute_id }}">
                                                        <tr class="attrcount">
                                                            <td>
                                                                <select name="attribute[]" id="attribute" class="form-control attribute-select" data-attr_value="{{ $count }}">
                                                                    @if (!empty($attribute))
                                                                        @foreach ($attribute as $item)
                                                                            @php $selected = ($item->id == $value->attribute_id) ? 'selected' : ''; @endphp
                                                                            <option value="{{ $item->id }}"
                                                                                data-attribute="{{ $item->id }}"
                                                                                {{ $selected }}>{{ $item->title }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control attrvalue-select select2" name="attrvalue{{ $count }}[]" id="attrvalue{{ $count }}" multiple>
                                                                    @if (!empty($attrvalues))
                                                                        @foreach ($attrvalues as $item1)
                                                                            @php 
                                                                            $arrAttrvalues = explode(",", $value->attrvalues);
                                                                            $selected = ($item1->attribute == $value->attribute_id && in_array($item1->id, $arrAttrvalues)  ) ? 'selected' : ''; 
                                                                            
                                                                            @endphp
                                                                            <option value="{{ $item1->id }}"
                                                                                {{ $selected }}>{{ $item1->value }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td><a href="javascript:;"
                                                                    class="btn btn-danger deleteRow">-</a></td>
                                                        </tr>
                                                    @endforeach
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
                                                    <input type="number" class="form-control" name="unit_price"
                                                        placeholder="Unit Price" value="{{ $products->unit_price }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Tax</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control tax" name="tax" id="">
                                                        <option value="" disabled selected>Select Tax</option>
                                                        @foreach ($tax as $item)
                                                            @if ($products->tax == $item->id)
                                                                <option value="{{ $item->id }}"
                                                                    data-percent="{{ $item->percent }}" selected>
                                                                    {{ $item->percent }}%</option>
                                                            @else
                                                                @if ($item->status == '1')
                                                                    <option value="{{ $item->id }}"
                                                                        data-percent="{{ $item->percent }}">
                                                                        {{ $item->percent }}%</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group taxable_price">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Taxable Price</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="taxable_price"
                                                        placeholder="Taxable Price"
                                                        value="{{ $products->taxable_price }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Quantity</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="quantity"
                                                        placeholder="Quantity" value="{{ $products->quantity }}">
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
                                                        placeholder="Select Date" value="{{ $products->date_range }}">
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
                                                                placeholder="Discount" value="{{ $products->discount }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control" name="discount_type" id="">
                                                        <option value="flat"
                                                            {{ $products->discount_type == 'flat' ? 'selected' : '' }}>Flat
                                                        </option>
                                                        <option value="percent"
                                                            {{ $products->discount_type == 'percent' ? 'selected' : '' }}>
                                                            Percent</option>
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
                                                    <textarea name="description" id="summernote" class="form-control" id="" cols="30" rows="4">{!! htmlspecialchars_decode($products->description) !!}</textarea>
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
                                                        placeholder="Meta Title" value="{{ $products->meta_title }}">
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
                                                        rows="4">{{ $products->meta_desc }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <span>Slug</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="slug"
                                                        placeholder="Slug" value="{{ $products->slug }}">
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
                                                    <span>Status</span> <small class="text-danger">*</small>
                                                </div>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="product_status"
                                                        style="width: 100%;">
                                                        <option value="1"
                                                            {{ $products->status == '1' ? 'selected' : '' }}>Published
                                                        </option>
                                                        <option value="0"
                                                            {{ $products->status == '0' ? 'selected' : '' }}>Draft</option>
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
                                                        <input type="checkbox" id="checkbox2" name="show_qty"
                                                            {{ $products->show_quantity == '1' ? 'checked' : '' }}>
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
                                                        <input type="checkbox" id="checkbox5" name="today_deal"
                                                            {{ $products->today_deal == '1' ? 'checked' : '' }}>
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
                                                        <option value="free"
                                                            {{ $products->shipping_charges == 'free' ? 'selected' : '' }}>
                                                            Free Shipping</option>
                                                        <option value="area"
                                                            {{ $products->shipping_charges == 'area' ? 'selected' : '' }}>
                                                            Area Wise</option>
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
                                                        placeholder="Shipping Days"
                                                        value="{{ $products->shipping_days }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
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
    @php
        $gallery = array_filter(explode(',', $products->gallery_img));
        $gallery_array = [];
        for ($i = 0; $i < count($gallery); $i++) {
            $g = (object) ['id' => $i + 1, 'src' => asset('products/' . $gallery[$i])];
            array_push($gallery_array, $g);
        }

    @endphp
@stop
@section('pageJsScripts')
    <script src="{{ asset('assets/js/tokenfield.js') }}"></script>
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
                '<input type="number" class="form-control" name="taxable_price" placeholder="Unit Price" value="' +
                total + '" readonly>' +
                '</div>' +
                '</div>';
            $('.taxable_price').html(row);
        });

        var count = $('.attrcount').length;
        $('thead').on('click', '.addRow', function() {
            count++;
            var tr = '<tr class="attrcount">' +
                        '<td>' +
                            '<select name="attribute[]" id="attribute" class="form-control attribute-select" data-attr_value="' + count + '">' +
                                '<option value="">Select an Attribute</option>' +
                                <?php foreach($attribute as $item){ ?> '<option value="{{ $item->id }}" data-attribute="{{ $item->id }}">{{ $item->title }}</option>' +
                        <?php }?> '</select>' +
                        '</td>' +
                        '<td>' +
                            '<select class="form-control attrvalue-select select2" name="attrvalue' + count + '[]" id="attrvalue' + count + '" multiple>' +
                                '<option value="" disabled selected >First Select Attribute</option>' +

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

        $(function() {

            var preloaded = <?php echo json_encode($gallery_array); ?>;

            $('.gallery-images1').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'gallery1',
                'label': 'Drag and Drop',
                preloadedInputName: 'old',
                maxFiles: 10,
                maxSize: 2 * 1024 * 1024,
            });

        });

        $('#tokenfield').tokenfield({
            autocomplete: {
                delay: 100
            },
            showAutocompleteOnFocus: true
        })
    </script>
@stop
