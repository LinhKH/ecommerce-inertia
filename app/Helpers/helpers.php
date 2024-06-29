<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;

if (!function_exists('user_wishlist')) {
    function user_wishlist()
    {
        $wishlist = 0;
        if (Session::has('user_id')) {
            $user = Session::get('user_id');
            $wishlist_items = Users::where('user_id', $user)->pluck('wishlist')->first();
            $wishlist = count(array_filter(explode(',', $wishlist_items)));
        }
        return $wishlist;
    }
}

if (!function_exists('user_cart')) {
    function user_cart()
    {
        $cart = 0;
        if (Session::has('user_id')) {
            $user = Session::get('user_id');
            $cart = Cart::where('product_user', $user)->count();
        }
        return $cart;
    }
}

if (!function_exists('all_category')) {
    function all_category()
    {
        return Category::select(['categories.*'])
            ->with('childrenCategories')
            ->where('categories.status', '1')
            ->get();
    }
}

if (!function_exists('get_parent_category')) {
    function get_parent_category($cat)
    {
        if ($cat) {
            if ($cat->parent_category == '0') {
                $child = Category::where('parent_category', $cat->id)->get();
                $cat->sub_category = $child;
                return $cat;
            } else {
                $parent = Category::where('id', $cat->parent_category)->first();
                $parent_cat = get_parent_category($parent);
                foreach ($parent_cat->sub_category as $row) {
                    $sub_child = Category::where('parent_category', $row->id)->get();
                    if ($row->id == $cat->id) {
                        $row->sub_category = $sub_child;
                    }
                }
                return $parent_cat;
            }
        }
    }
}

if (!function_exists('all_brands')) {
    function all_brands()
    {
        return Brand::select(['brands.*'])->where('status', 'publish')->get();
    }
}


if (!function_exists('site_settings')) {
    function site_settings()
    {
        return DB::table('general_settings')->first();
    }
}

if (!function_exists('social_links')) {
    function social_links()
    {
        return DB::table('social_links')->first();
    }
}


if (!function_exists('site_pages')) {
    function site_pages()
    {
        return DB::table('pages')->where('status', '1')->get();
    }
}

if (!function_exists('product_rating')) {
    function product_rating($id)
    {
        return DB::table('reviews')->select([DB::raw('COUNT(reviews.product) as rating_col'), DB::raw('SUM(reviews.rating) as rating_sum')])->where('product', $id)->first();
    }
}

if (!function_exists('get_product_price')) {
    function get_product_price($id)
    {
        $price = (object)[];
        $product = Product::select(['products.taxable_price', 'products.date_range', 'products.discount', 'products.discount_type', 'flash_products.product_discount', 'flash_products.product_discount_type', 'flash_deals.flash_date_range', 'flash_deals.status as deal_status'])
            ->leftJoin('flash_products', 'products.id', '=', 'flash_products.product_id')
            ->leftJoin('flash_deals', 'flash_products.deals_id', '=', 'flash_deals.id')
            ->where('products.id', $id)->first();
        if ($product->taxable_price != NULL && $product->taxable_price != '') {
            $price->new_price = $product->taxable_price;
            $price->old_price = $product->taxable_price;
        } else {
            $price->new_price = '0';
            $price->old_price = '0';
        }
        $price->discount = '';
        if ($product->flash_date_range != '' && $product->deal_status == '1') {
            $datetimes = explode('-', $product->flash_date_range);
            $currentDatetimes = date('Y-m-d H:i A');
            if ($product->flash_date_range != '') {
                $startDatetimes = date('Y-m-d H:i A', strtotime("$datetimes[0]"));
                $endDatetimes = date('Y-m-d H:i A', strtotime("$datetimes[1]"));
            } else {
                $startDatetimes = '';
                $endDatetimes = '';
            }
            if (($currentDatetimes >= $startDatetimes) && ($currentDatetimes <= $endDatetimes)) {
                $discount_amt = 0;
                if ($product->product_discount_type == 'flat') {
                    $price->new_price = $product->taxable_price - $product->product_discount;
                    $price->discount = '-'. $product->product_discount;
                } elseif ($product->product_discount_type == 'percent') {
                    $discount_amt = $product->taxable_price * $product->product_discount / 100;
                    $price->new_price = (int)$product->taxable_price - (int)$discount_amt;
                    $price->discount = $product->product_discount . '%';
                }
            }
        } elseif ($product->date_range != '' && $product->discount != '') {
            $date = explode('-', $product->date_range);
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            if ($product->date_range != '') {
                $startDate = date('Y-m-d', strtotime("$date[0]"));
                $endDate = date('Y-m-d', strtotime("$date[1]"));
            } else {
                $startDate = '';
                $endDate = '';
            }
            if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
                if ($product->discount_type == 'flat') {
                    $price->new_price = $product->taxable_price - $product->discount;
                    $price->discount = $product->discount;
                } elseif ($product->discount_type == 'percent') {
                    $tax_total = $product->taxable_price * $product->discount / 100;
                    $price->new_price = (int)$product->taxable_price - (int)$product->discount;
                    $price->discount = $product->discount . '%';
                }
            }
        }
        return $price;
    }
}

if (!function_exists('get_category_breadcrumb')) {
    function get_category_breadcrumb($id, $arr = [])
    {
        array_push($arr, $id);
        $parent = Category::where('id', $id)->pluck('parent_category')->first();
        if ($parent != '0') {
            $arr2 = get_category_breadcrumb($parent, $arr);
            $arr = array_unique(array_merge($arr, $arr2));
        }
        return $arr;
    }
}

if (!function_exists('get_category_children')) {
    function get_category_children($id, $arr = [])
    {
        array_push($arr, $id);
        $parent = Category::where('parent_category', $id)->pluck('id')->all();

        if (!empty($parent)) {
            foreach ($parent as $id_child) {
                $arr2 = get_category_children($id_child, $arr);
                $arr = array_unique(array_merge($arr, $arr2));
            }
        }

        return $arr;
    }
}
