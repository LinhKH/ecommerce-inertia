<?php

namespace App\Http\Controllers;

//use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Attribute_value;
use App\Models\Attrvalue;
use App\Models\Cart;
use App\Models\City;
use App\Models\FlashDeal;
use App\Models\FlashProduct;
use App\Models\Review;
use App\Models\OrderProducts;
use App\Models\Page;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $banner = Banner::select(['banner.*'])->get();

        $flash_deals = FlashDeal::select(['flash_deals.*'])
            ->orderBy('flash_deals.id', 'DESC')
            ->where('status', '1')
            ->limit(3)
            ->get();

        $new_products = Product::select(['products.id', 'products.product_name', 'products.gallery_img', 'products.thumbnail_img', 'products.slug', 'products.unit_price', 'products.taxable_price', 'products.discount', 'brands.brand_name', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->leftjoin('reviews', 'reviews.product', '=', 'products.id')
            ->where('products.status', '1')
            ->where('products.quantity', '>', '1')
            ->orderBy('products.id', 'DESC')
            ->groupBy('products.id')
            ->limit(8)
            ->get();
        if ($new_products) {
            foreach ($new_products as $product) {
                $price = get_product_price($product->id);
                $product->discount = $price->old_price - $price->new_price;
                $product->discount_percent = $price->discount;
            }
        }

        $flash_products = FlashProduct::select(['products.id', 'products.product_name', 'products.thumbnail_img', 'products.taxable_price', 'products.discount', 'products.slug', 'brands.brand_name', 'flash_deals.flash_date_range', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('flash_deals', 'flash_deals.id', '=', 'flash_products.deals_id')
            ->leftjoin('products', 'products.id', '=', 'flash_products.product_id')
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->leftjoin('reviews', 'reviews.product', '=', 'products.id')
            ->where('products.status', '1')
            ->where('flash_deals.status', '1')
            ->orderBy('flash_products.id', 'DESC')
            ->groupBy('products.id')
            ->limit(10)
            ->get();
            // dd($flash_products);

        if ($flash_products) {
            foreach ($flash_products as $product) {
                $price = get_product_price($product->id);
                $product->discount = $price->old_price - $price->new_price;
                $product->discount_percent = $price->discount;
            }
        }


        $today_deals = Product::select(['products.id', 'products.product_name', 'products.gallery_img', 'products.thumbnail_img', 'products.slug', 'products.taxable_price', 'products.discount', 'brands.brand_name', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->leftjoin('reviews', 'reviews.product', '=', 'products.id')
            ->where('products.status', '1')
            ->where('products.today_deal', '1')
            ->where('products.quantity', '>', '1')
            ->orderBy('products.id', 'DESC')
            ->groupBy('products.id')
            ->limit(10)
            ->get();

        if ($today_deals) {
            foreach ($today_deals as $product) {
                $price = get_product_price($product->id);
                $product->discount = $price->old_price - $price->new_price;
                $product->discount_percent = $price->discount;
            }
        }

        $review = Review::select(['reviews.id', 'reviews.rating', 'products.id as product_id', 'products.slug', 'products.thumbnail_img', 'products.product_name', 'products.unit_price', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('products', 'products.id', '=', 'reviews.product')
            ->where('reviews.rating', '5')
            ->groupBy('products.id')
            ->limit(5)->get();
        if ($review) {
            foreach ($review as $product) {
                $price = get_product_price($product->product_id);
                $product->discount = $price->old_price - $price->new_price;
                $product->discount_percent = $price->discount;
            }
        }

        $orderProducts = OrderProducts::select(['order_products.product_id', 'products.product_name', 'products.slug', 'products.thumbnail_img', 'products.unit_price', 'products.discount', DB::raw('SUM(order_products.product_qty) AS total_qty'), DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftJoin('products', 'products.id', '=', 'order_products.product_id')
            ->leftjoin('reviews', 'reviews.product', '=', 'products.id')
            ->groupBy('order_products.product_id', 'products.product_name', 'products.thumbnail_img', 'products.unit_price')
            ->orderBy('total_qty', 'desc')->limit(5)->get();

        if ($orderProducts) {
            foreach ($orderProducts as $product) {
                $price = get_product_price($product->product_id);
                $product->discount = $price->old_price - $price->new_price;
                $product->discount_percent = $price->discount;
            }
        }

        return Inertia::render('Index', ['banner' => $banner, 'flash_deals' => $flash_deals, 'flash_products' => $flash_products, 'latest_products' => $new_products, 'today_deal_products' => $today_deals, 'rating' => $review, 'orderProducts' => $orderProducts]);
    }

    // today deals page
    public function todayDeals()
    {
        Paginator::useBootstrap();
        $today_deals = Product::select(['products.id', 'products.product_name', 'products.taxable_price', 'products.gallery_img', 'products.thumbnail_img', 'products.slug', 'brands.brand_name'])
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->where('products.status', '1')
            ->where('products.today_deal', '1')
            ->where('products.quantity', '>', '1')
            ->orderBy('products.id', 'DESC')
            ->paginate(12);
        foreach ($today_deals as $product) {
            $price = get_product_price($product->id);
            $product->discount = $price->old_price - $price->new_price;
            $product->discount_percent = $price->discount;
        }
        return Inertia::render('AllTodayDeals', ['today_deal_products' => $today_deals]);
    }



    // single product page
    public function productpage($text)
    {
        $cart = [];
        if (session()->has('user_id')) {
            $user = session()->get('user_id');
            $cart = Cart::where('product_user', $user)->pluck('product_id')->toArray();
            $cart = array_map('intval', $cart);
        }
        $product = Product::select('products.*', 'brands.brand_name', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum'))
            ->leftJoin('brands', 'brands.id', '=', 'products.brand')
            ->leftJoin('reviews', 'products.id', '=', 'reviews.product')
            ->where('products.slug', $text)
            ->first();

        $price = get_product_price($product->id);
        $product->discount = $price->old_price - $price->new_price;
        $product->discount_percent = $price->discount;


        $reviews = Review::select(['reviews.*', 'users.name'])
            ->leftJoin('users', 'reviews.user', '=', 'users.user_id')
            ->where('reviews.product', $product->id)
            ->where('reviews.approved', '1')
            ->where('reviews.hide_by_admin', '0')
            ->get();

        $related = Product::select(['products.*', 'brands.brand_name', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->leftjoin('reviews', 'products.id', '=', 'reviews.product')
            ->where('products.category', $product->category)
            ->where('products.status', '1')
            ->groupBy('products.id')
            ->orderBy('products.id', 'DESC')
            ->limit(10)
            ->get();

        $colors = Color::select(['colors.*'])->get();
        $attrvalues = Attrvalue::select(['attrvalues.*'])->get();
        $attributes = Attribute_value::select(['attributes_values.*', 'attributes.title'])
            ->leftjoin('attributes', 'attributes.id', '=', 'attributes_values.attribute_id')
            ->where(['attributes_values.product_id' => $product->id])->groupBy('attributes_values.attrvalues')->get();

        $cities = City::select(['cities.*', 'states.state_name'])
            ->leftjoin('states', 'states.id', '=', 'cities.state')
            ->get();

        return Inertia::render('Product', ['attrvalues' => $attrvalues, 'attributes' => $attributes, 'colors' => $colors, 'product' => $product, 'cities' => $cities, 'related' => $related, 'cart' => $cart, 'reviews' => $reviews]);
    }


    // category and search page
    public function search_products(Request $request, $slug = '')
    {
        Paginator::useBootstrap();
        if ($request->category && $request->category != 'all') {
            $slug = $request->category;
        }

        $where = '';

        if ($request->sort == 'h-l') {
            $order = 'products.unit_price DESC';
        } else if ($request->sort == 'l-h') {
            $order = 'products.unit_price ASC';
        } elseif ($request->sort == 'oldest') {
            $order = 'products.id ASC';
        } else {
            $order = 'products.id DESC';
        }

        if ($slug != '' && $slug != NULL) {
            // return var_dump($slug);
            $cat_detail = Category::select('*')->where('categories.category_slug', $slug)->first();
            $cat_array =  get_parent_category($cat_detail);

            $breadcrumb_ids = get_category_breadcrumb($cat_detail->id);
            $breadcrumb = Category::select(['id', 'category_name', 'category_slug'])->whereIn('id', $breadcrumb_ids)->orderBy('id', 'ASC')->get();

            if (!$cat_detail && $slug != '') {
                return abort(404);
            }

            $brands = Brand::whereRaw("FIND_IN_SET({$cat_detail->id},brand_subcat)")->get();

            $child_array = $this->get_child_id($cat_detail->id, []);
            $child = implode(',', array_filter($child_array));
            $where .= "products.category IN ({$child})";
            if ($request->keyword && $request->keyword != '') {
                $keyword = $request->keyword;
                $where .= "AND products.product_name LIKE '%{$request->keyword}%' OR products.tags LIKE '%{$request->keyword}%'";
            } else {
                $keyword = null;
            }
        } else {
            $keyword = $request->keyword;
            if ($keyword != '') {
                $where .= "products.product_name LIKE '%{$request->keyword}%' OR products.tags LIKE '%{$request->keyword}%'";
            }
            $cat_detail = null;
            $cat_array = null;
            $brands = null;
            $breadcrumb = null;
        }
        if ($request->min_price != '' && $request->max_price != '') {
            if ($where != '') {
                $where .= ' AND ';
            }
            $where .= 'products.taxable_price BETWEEN ' . $request->min_price . ' AND ' . $request->max_price;
        }
        if ($request->brand && $request->brand != '') {
            if ($where != '') {
                $where .= ' AND ';
            }
            $where .= 'products.brand IN (' . implode(',', $request->brand) .')';
        }

        $limit = 6;
        if ($where != '') {
            $products = Product::select(['products.*', 'brands.brand_name', DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
                ->leftJoin('brands', 'brands.id', '=', 'products.brand')
                ->leftJoin('reviews', 'reviews.product', '=', 'products.id')
                ->whereRaw($where)
                ->groupBy('products.id')
                ->orderByRaw($order)
                ->paginate($limit);
        } else {
            $products = Product::select(['products.*', 'brands.brand_name'])
                ->leftJoin('brands', 'brands.id', '=', 'products.brand')
                ->paginate($limit);
        }
        $url_search = url()->current();

        return Inertia::render('AllProducts', ['keyword' => $keyword, 'cat_detail' => $cat_detail, 'cat_array' => $cat_array, 'products' => $products, 'brands' => $brands, 'limit' => $limit, 'breadcrumb' => $breadcrumb, 'url_search' => $url_search]);
    }

    public function get_child_id($id, $ids)
    {
        array_push($ids, $id);
        $child = Category::where('parent_category', $id)->get();
        if (!empty($child)) {
            foreach ($child as $row) {
                $child_ids = $this->get_child_id($row->id, $ids);
                $ids = array_unique(array_merge($ids, $child_ids));
            }
        }
        return $ids;
    }

    // all flash deals page
    public function allflashdeals()
    {
        Paginator::useBootstrap();
        $flash_deals = FlashDeal::select(['flash_deals.*'])
            ->where('status', '1')
            ->orderBy('flash_deals.id', 'DESC')
            ->paginate(8);
        return Inertia::render('AllFlashDeals', ['flash_deals' => $flash_deals]);
    }
    // flash deal products page
    public function flashproducts($text)
    {
        Paginator::useBootstrap();
        $flash_deal = FlashDeal::select(['flash_deals.*'])
            ->where(['flash_deals.flash_slug' => $text])
            ->first();
        $flash_products = FlashProduct::select(['products.id', 'products.product_name', 'products.taxable_price', 'products.thumbnail_img', 'products.slug', 'brands.brand_name', 'flash_deals.status', 'flash_deals.flash_date_range',
                            DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])
            ->leftjoin('flash_deals', 'flash_deals.id', '=', 'flash_products.deals_id')
            ->leftjoin('products', 'products.id', '=', 'flash_products.product_id')
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->leftjoin('reviews', 'reviews.product', '=', 'products.id')
            ->where('flash_products.deals_id', $flash_deal->id)
            ->where('flash_deals.status', '1')
            ->orderBy('flash_products.id', 'DESC')
            ->groupBy('products.id')
            ->paginate(8);

        foreach ($flash_products as $product) {
            $price = get_product_price($product->id);
            $product->discount = $price->old_price - $price->new_price;
            $product->discount_percent = $price->discount;
        }
        return Inertia::render('FlashDealProducts', ['flash_deal' => $flash_deal, 'flash_products' => $flash_products]);
    }

    // all flash products page
    public function allflashproducts()
    {
        Paginator::useBootstrap();
        $flash_products = FlashProduct::select(['flash_products.*', 'products.id', 'products.product_name', 'products.taxable_price', 'products.thumbnail_img', 'products.slug', 'brands.brand_name', 'flash_deals.status', 'flash_deals.flash_date_range'])
            ->leftjoin('flash_deals', 'flash_deals.id', '=', 'flash_products.deals_id')
            ->leftjoin('products', 'products.id', '=', 'flash_products.product_id')
            ->leftjoin('brands', 'brands.id', '=', 'products.brand')
            ->orderBy('flash_products.id', 'DESC')
            ->paginate(8);
        foreach ($flash_products as $product) {
            $price = get_product_price($product->id);
            $product->discount = $price->old_price - $price->new_price;
            $product->discount_percent = $price->discount;
        }
        return Inertia::render('AllFlashProducts', ['flash_products' => $flash_products]);
    }

    // custom page
    public function site_pages($slug)
    {
        $page = Page::where('page_slug', $slug)->first();
        if ($page) {
            return Inertia::render('Single', ['page' => $page]);
        } else {
            return abort('404');
        }
    }
}
