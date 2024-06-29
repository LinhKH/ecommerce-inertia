<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Attrvalue;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\Attribute_value;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = Product::latest()->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    if ($row->thumbnail_img != '') {
                        $img = '<img src="' . asset("products/" . $row->thumbnail_img) . '" width="80px">';
                    } else {
                        $img = '<img src="' . asset("products/") . '" width="100px">';
                    }
                    return $img;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == '1') {
                        $status = '<span class="badge badge-success">Published</span>';
                    } else {
                        $status = '<span class="badge badge-warning">Draft</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="products/' . $row->id . '/edit" class="btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete-product btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tax = Tax::all();
        // $category = Category::all();
        $category = Category::where('parent_category', 0)
            ->with('childrenCategories')
            ->get();
        // $subcategory = Subcategory::all();
        $brand = Brand::all();
        $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
            ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
            ->get();

        $attribute = Attribute::select(['attributes.*'])
            ->get();

        $colors = Color::select(['colors.*'])->get();
        return view('admin.products.create', ['colors' => $colors, 'attrvalues' => $attrvalues, 'tax' => $tax, 'category' => $category, 'brand' => $brand, 'attribute' => $attribute]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->input();

        $request->validate([
            'product_name' => 'required',
            'category' => 'required',
            // 'sub_category'=>'required',
            // 'brand'=>'required',
            // 'unit'=>'required',
            'min_qty' => 'required',
            'tags' => 'required',
            // 'barcode'=>'required',
            'thumbnail_img' => 'image|mimes:jpeg,png,jpg|max:2048',
            'unit_price' => 'required',
            'tax' => 'required',
            'quantity' => 'required',
            // 'product_status'=>'required',
            'shipping_charges' => 'required',
            'shipping_days' => 'required',
        ]);

        if ($request->thumbnail_img) {
            $image = $request->thumbnail_img->getClientOriginalName();
            $request->thumbnail_img->move(public_path('products'), $image);
        } else {
            $image = '';
        }

        $gallary = [];
        if ($request->hasfile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('products'), $name);
                $gallary[] = $name;
            }
        }

        if ($request->refundable) {
            $refundable = 1;
        } else {
            $refundable = 0;
        }

        if ($request->show_qty) {
            $show_qty = 1;
        } else {
            $show_qty = 0;
        }

        if ($request->today_deal) {
            $today_deal = 1;
        } else {
            $today_deal = 0;
        }

        $slug = str_replace(array('_', ' ',), '-', strtolower($request->input("product_name")));
        $meta_title = str_replace(array('_', ' ',), '-', strtolower($request->input('product_name')));

        $products = new Product();
        $products->thumbnail_img = $image;
        $products->gallery_img = implode(',', $gallary);
        $products->product_name = $request->input('product_name');
        $products->category = $request->input('category');
        // $products->subcategory = $request->input('sub_category');
        $products->brand = $request->input('brand');
        $products->unit = $request->input('unit');
        $products->min_qty = $request->input('min_qty');
        $products->tags = $request->input('tags');
        $products->barcode = $request->input('barcode');
        $products->refundable = $refundable;
        if ($request->color) {
            $products->colors = implode(',', $request->input('color'));
        }
        $products->unit_price = $request->input('unit_price');
        $products->tax = $request->input('tax');
        $products->taxable_price = $request->input('taxable_price');
        $products->quantity = $request->input('quantity');
        $products->date_range = $request->input('datefilter');
        $products->discount = $request->input('discount');
        $products->discount_type = $request->input('discount_type');
        $products->description = htmlspecialchars($request->input('description'));
        $products->meta_title = $meta_title;
        $products->meta_desc = $request->input('meta_desc');
        $products->slug = $slug;
        $products->show_quantity = $show_qty;
        $products->today_deal = $today_deal;
        $products->shipping_charges = $request->input('shipping_charges');
        $products->shipping_days = $request->input('shipping_days');
        $products->status = $request->input('product_status');
        $result = $products->save();

        if ($request->attribute) {
            $attribute_id = $request->input('attribute');

            for ($i = 0; $i < count($attribute_id); $i++) {
                $datasave = [
                    'attribute_id' => $attribute_id[$i],
                    'attrvalues' => implode(',', $request->input('attrvalue' . $i + 1)),
                    'product_id' => $products->id
                ];
                Attribute_value::insert($datasave);
            }
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $products = Product::where(['id' => $id])->first();
        $tax = Tax::all();
        $category = Category::where('parent_category', 0)
            ->with('childrenCategories')
            ->get();
        // $subcategory = Subcategory::all();
        $brand = Brand::all();
        $colors = Color::select(['colors.*'])->get();
        $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
            ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
            ->get();

        $attribute = Attribute::select(['attributes.*'])
            ->get();
        $attribute_values = Attribute_value::where(['product_id' => $id])->get();
        return view('admin.products.edit', ['attribute_values' => $attribute_values, 'attribute' => $attribute, 'attrvalues' => $attrvalues, 'colors' => $colors, 'products' => $products, 'tax' => $tax, 'category' => $category, 'brand' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // return $request->input();
        $request->validate([
            'product_name' => 'required',
            'category' => 'required',
            'min_qty' => 'required',
            'tags' => 'required',
            'thumbnail_img' => 'image|mimes:jpeg,png,jpg|max:2048',
            'unit_price' => 'required',
            'tax' => 'required',
            'quantity' => 'required',
            'shipping_charges' => 'required',
            'shipping_days' => 'required'
        ]);

        if ($request->thumbnail_img != '') {
            $path = public_path() . '/products/';
            //code for remove old file
            if ($request->old_img != '' && $request->old_img != null) {
                $file_old = $path . $request->old_img;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
            }
            //upload new file
            $file = $request->thumbnail_img;
            $image = $request->thumbnail_img->getClientOriginalName();
            $file->move($path, $image);
        } else {
            $image = $request->old_img;
        }

        $gallery = array_filter(explode(',', $request->old_gallery));
        if (!empty($request->old)) {
            for ($j = 0; $j < count($gallery); $j++) {
                if (!in_array($j + 1, $request->old)) {
                    $img = $gallery[$j];
                    if (file_exists(public_path('products/' . $img))) {
                        unlink(public_path('products/') . $img);
                    }
                    unset($gallery[$j]);
                }
            }
        }
        if ($request->hasfile('gallery1')) {
            foreach ($request->file('gallery1') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('products'), $name);
                $gallery[] = $name;
            }
        }

        if ($request->refundable) {
            $refundable = 1;
        } else {
            $refundable = 0;
        }

        if ($request->show_qty) {
            $show_qty = 1;
        } else {
            $show_qty = 0;
        }

        if ($request->today_deal) {
            $today_deal = 1;
        } else {
            $today_deal = 0;
        }

        if ($request->meta_title != '') {
            $meta_title = str_replace(array('_', ' ',), '-', strtolower($request->input('meta_title')));
        } else {
            $meta_title = str_replace(array('_', ' ',), '-', strtolower($request->input('product_name')));
        }

        if ($request->slug != '') {
            $slug = str_replace(array('_', ' ',), '-', strtolower($request->input('slug')));
        } else {
            $slug = str_replace(array('_', ' ',), '-', strtolower($request->input('product_name')));
        }

        if ($request->color) {
            $colors = implode(',', $request->input('color'));
        } else {
            $colors = '';
        }

        $products = Product::where(['id' => $id])->update([
            'thumbnail_img' => $image,
            'gallery_img' => implode(',', $gallery),
            'product_name' => $request->input('product_name'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'unit' => $request->input('unit'),
            'min_qty' => $request->input('min_qty'),
            'tags' => $request->input('tags'),
            'barcode' => $request->input('barcode'),
            'refundable' => $refundable,
            'colors' => $colors,
            'unit_price' => $request->input('unit_price'),
            'tax' => $request->input('tax'),
            'taxable_price' => $request->input('taxable_price'),
            'quantity' => $request->input('quantity'),
            'date_range' => $request->input('datefilter'),
            'discount' => $request->input('discount'),
            'discount_type' => $request->input('discount_type'),
            'description' => htmlspecialchars($request->input('description')),
            'meta_title' => $meta_title,
            'meta_desc' => $request->input('meta_desc'),
            'slug' => $request->input('slug'),
            'status' => $request->input('product_status'),
            'show_quantity' => $show_qty,
            'today_deal' => $today_deal,
            'shipping_charges' => $request->input('shipping_charges'),
            'shipping_days' => $request->input('shipping_days')
        ]);


        if (!empty($request->input('attribute'))) {

            $attribute_id = $request->input('attribute');
            if ($request->attr_id) {
                $attr_id = $request->input('attr_id');
                DB::table('attributes_values')->whereIn('id', $attr_id)->delete();
            }
            for ($i = 0; $i < count($attribute_id); $i++) {
                $datasave = [
                    'attribute_id' => $attribute_id[$i],
                    'attrvalues' => implode(',', $request->input('attrvalue' . $i + 1)),
                    'product_id' => $id
                ];
                Attribute_value::insert($datasave);
            }
        } else {
            if ($request->attr_id) {
                $attr_id = $request->input('attr_id');
                DB::table('attributes_values')->whereIn('id', $attr_id)->delete();
            }
        }
        return $products;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $destroy = Product::where(['id' => $id])->delete();
        return $destroy;
    }

    public function get_attrvalue(Request $request)
    {
        if ($request->input()) {
            $attribute = $request->attribute;

            $attrvalues = Attrvalue::where(['attribute' => $attribute])->get();

            $output = '<option disabled value="">Select Attribute Value</option>';
            if (!empty($attrvalues)) {
                foreach ($attrvalues as $row) {
                    $output .= '<option value="' . $row['id'] . '">' . $row['value'] . '</option>';
                }
            } else {
                $output = '<option disabled selected value=">No Attribute Value Found</option>';
            }
            return $output;
        }
    }
}
