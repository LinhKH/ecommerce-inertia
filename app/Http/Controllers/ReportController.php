<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    //

    public function product_sale(Request $request)
    {

        if ($request->ajax()) {
            if ($request->category != 'all') {
                $categoryIDs = get_category_children($request->category);
                $data = Product::select(['products.*', 'order_products.product_id', \DB::raw("SUM(order_products.product_qty) as total_id")])
                    ->leftjoin('order_products', 'order_products.product_id', '=', 'products.id')
                    ->groupBy('products.id')
                    ->whereIn('category', $categoryIDs)
                    ->orderBy('products.id', 'desc')->get();
            } else {
                $data = Product::select(['products.*', 'order_products.product_id', \DB::raw("SUM(order_products.product_qty) as total_id")])
                    ->leftjoin('order_products', 'order_products.product_id', '=', 'products.id')
                    ->groupBy('products.id')
                    ->orderBy('products.id', 'desc')->get();

            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $category = Category::where('parent_category', 0)
            ->with('childrenCategories')
            ->get();
        return view('admin.product-sale.index', ['category' => $category]);
    }

    public function product_stock(Request $request)
    {
        if ($request->ajax()) {
            if ($request->category != 'all') {
                $categoryIDs = get_category_children($request->category);
                $data = Product::select('product_name', 'quantity')->whereIn('category', $categoryIDs)->orderBy('id', 'desc')->get();
            } else {
                $data = Product::select('product_name', 'quantity')->orderBy('id', 'desc')->get();
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        $category = Category::where('parent_category', 0)
            ->with('childrenCategories')
            ->get();
        return view('admin.product-stock.index', ['category' => $category]);
    }
}
