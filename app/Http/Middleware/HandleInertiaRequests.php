<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Users;
use App\Models\Cart;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        if (file_exists(storage_path('installed'))) {
            $user = null;
            $userSession = null;
            $wishlist = 0;
            $cart = 0;

            if (Session::has('user_name')) {
                $userSession['user_name'] = Session::get('user_name');
                $userSession['user_id'] = Session::get('user_id');
                $userSession['user_city'] = Session::get('user_city');
            }

            if (Session::has('user_id')) {
                $user = Session::get('user_id');
                $wishlist_items = Users::where('user_id', $user)->pluck('wishlist')->first();
                $wishlist = count(array_filter(explode(',', $wishlist_items)));
            }

            if (Session::has('user_id')) {
                $user = Session::get('user_id');
                $cart = Cart::where('product_user', $user)->count();
            }
            $allCategories = Category::all();

            return array_merge(parent::share($request), [
                //
                // Synchronously...
                'all_category' => $allCategories,
                'generalSettings' => DB::table('general_settings')->first(),
                // 'categories' => Category::where('parent_category','0')->get(),
                'sitePages' => DB::table('pages')->where('status', '1')->get(),
                'user' => $user,
                'userSession' => $userSession,
                'userWishlist' => $wishlist,
                'userCart' => $cart,
                'flash' => [
                    // 'error' => $request->session()->get('error'),
                    'error' => fn () => $request->session()->get('error'),
                    'success' => fn () => $request->session()->get('success'),
                    // 'success' => $request->session()->get('success'),
                ],

                //'success' => $request->session()->get('success'),
                //'error' => $request->session()->get('error'),

            ]);
        } else {
            return array_merge(parent::share($request), []);
        }
    }
}
