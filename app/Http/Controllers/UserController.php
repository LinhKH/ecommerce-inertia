<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Models\Banner;
use App\Models\Users;
use App\Models\Category;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Attribute;
use App\Models\Attrvalue;
use App\Models\Color;
use App\Models\PaymentData;
use App\Models\PaymentMethod;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Review;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Exception;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Pagination\Paginator;


class UserController extends Controller
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
            $data = Users::select(['users.*', 'cities.city_name', 'states.state_name', 'countries.country_name'])
                ->leftJoin('cities', 'cities.id', '=', 'users.city')
                ->leftJoin('states', 'states.id', '=', 'users.state')
                ->leftJoin('countries', 'countries.id', '=', 'users.country')
                ->orderBy('user_id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == '1') {
                        $btn = '<button class="btn btn-warning btn-sm userBlock" data-status="' . $row->status . '" data-id="' . $row->user_id . '">Block</button>';
                    } else {
                        $btn = '<button class="btn btn-success btn-sm userBlock" data-status="' . $row->status . '" data-id="' . $row->user_id . '">Unblock</button>';
                    }
                    return $btn;
                })
                ->make(true);
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (session()->has('user_name')) {
            return Inertia::render('/');
            //  return redirect('/');
        } else {
            //return view('public.signup');
            return Inertia::render('SignUp');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = new Users();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = Hash::make($request->input('password'));
        $u = $user->save();
        return back()->with(['success' => 'Account Created Successfully.']);
        // return $u;
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = session()->get('user_id');

        $request->validate([
            'name' => 'required',
            // 'img'=>'mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required',
        ]);

        $old_img = Users::where('user_id', $id)->pluck('user_img')->first();


        if ($request->img && $request->img != '') {
            $path = public_path() . '/users/';
            //code for remove old file
            if ($old_img != ''  && $old_img != null) {
                $file_old = $path . $old_img;
                if (file_exists($file_old)) {
                    unlink($file_old);
                }
            }
            //upload new file
            $file = $request->img;
            $image = $request->img->getClientOriginalName();
            $file->move($path, $image);
        } else {
            $image = $old_img;
        }
        // return $image;
        $users = Users::where(['user_id' => $id])->update([
            'user_img' => $image,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'state' => $request->input('state'),
            'pin_code' => $request->input('code'),
            'country' => $request->input('country'),
        ]);
        $request->session()->put('user_city', $request->city);
        return back()->with(['success' => 'User Profile Updated Successfully.']);
        //return $users;

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
    }

    public function changeStatus(Request $request)
    {
        if ($request->post()) {
            $id = $request->post('uId');
            $status = $request->post('status');

            $user = Users::where('user_id', $id)->update([
                'status' => $status,
            ]);
            return $user;
        }
    }

    public function login(Request $request)
    {

        if ($request->input()) {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $login = Users::where(['email' => $request->username])->first();

            if (empty($login)) {
                return back()->with(['error' => 'Username Does not Exists.']);
            } else if ($login->status == '0') {
                return back()->with(['error' => 'The Email / Username is Blocked.']);
            } else {
                if (Hash::check($request->password, $login->password)) {
                    $request->session()->put('user', '1');
                    $request->session()->put('user_name', $login->name);
                    $request->session()->put('user_id', $login->user_id);
                    $request->session()->put('user_city', $login->city);
                    //    return back()->with(['success'=>' Logged in Successfully.']);
                    Session::flash('success', 'Logged in Successfully.');
                } else {
                    Session::flash('error', 'Username and Password does not matched.');
                    //    return back()->with(['error'=>'Username and Password does not matched.']);
                }
                return Inertia::render('UserLogin');
            }
        } else {
            if (Session::has('user_name')) {
                return redirect('/');
            } else {
                return Inertia::render('UserLogin');
            }
            // return Inertia::render('UserLogin');
        }
    }

    public function login_form(Request $request)
    {
        // if($request->input()){
        //     $request->validate([
        //         'username'=>'required',
        //         'password'=>'required',
        //     ]);

        //     $login = Users::where(['email'=>$request->username])->first();

        //     if(empty($login)){
        //         return back()->with(['error'=>'Username Does not Exists.']);
        //     }else if($login->status == '0'){
        //         return back()->with(['error'=>'The Email / Username is Blocked.']);
        //     }else{
        //         if(Hash::check($request->password,$login->password)){
        //             $request->session()->put('user','1');
        //             $request->session()->put('user_name',$login->name);
        //             $request->session()->put('user_id',$login->user_id);
        //             $request->session()->put('user_city',$login->city);
        //            return back()->with(['success'=>' Logged in Successfully.']);
        //         }else{
        //            return back()->with(['error'=>'Username and Password does not matched.']);
        //         }
        //     }
        // }else{
        //     return Inertia::render('UserLogin');
        // }
    }

    public function logout()
    {
        session()->forget('user');
        session()->forget('user_name');
        session()->forget('user_id');
        session()->forget('user_city');
        // Session::flash('success','Logged Out Successfully.');
        return back()->with(['success' => ' logout']);
    }

    public function changepassword()
    {
        if (session()->has('user_name')) {
            $banner = Banner::select(['banner.*'])->get();

            return Inertia::render('ChangePassword');
            // return view('public.changepassword',['banner'=>$banner]);
        } else {
            return Inertia::render('UserLogin');
            //return redirect('user_login');
        }
    }

    public function change_password(Request $request)
    {
        if ($request->input()) {
            $request->validate([
                'password' => 'required',
                'new_pass' => 'required',
                're_pass' => 'required',
            ]);

            $user_id = session()->get('user_id');

            $select = Users::where('user_id', $user_id)->pluck('password')->first();

            if (Hash::check($request->password, $select)) {
                $update = Users::where('user_id', $user_id)->update([
                    'password' => Hash::make($request->new_pass)
                ]);
                return back()->with(['success' => 'Change Password Successfully']);
                //return '1';
            } else {
                return back()->with(['error' => 'Please Enter Correct Old Password']);
                //return response()->json(['password'=>'Please Enter Correct Old Password']);
            }
        }
    }

    public function my_profile()
    {
        if (session()->has('user_name')) {
            $user_id = session()->get('user_id');
            $user = Users::where(['user_id' => $user_id])->first();
            $country = Country::select(['countries.*'])->get();
            $state = State::select(['states.*'])->where('status', 1)->get();
            $city = City::select(['cities.*'])->where('status', 1)->get();
            return Inertia::render('MyProfile', ['user' => $user, 'city' => $city, 'state' => $state, 'country' => $country]);
            // return view('public.my-profile',['user'=>$user,'city'=>$city,'state'=>$state,'country'=>$country]);
        } else {
            // return redirect('user_login');
            return Inertia::render('UserLogin');
        }
    }

    public function add_wishlist(Request $request)
    {
        $id = $request->id;
        $user = Session::get('user_id');
        $wishlist = Users::where('user_id', $user)->pluck('wishlist')->first();
        if (!empty($wishlist)) {
            $w_array = array_filter(explode(',', $wishlist));
        } else {
            $w_array = [];
        }
        if (!in_array($id, $w_array)) {
            array_push($w_array, $id);
        }
        $count = count($w_array);
        $w_array = implode(',', $w_array);
        Users::where('user_id', $user)->update([
            'wishlist' => $w_array
        ]);
        return back()->with(['success' => 'WishList Item Added', 'count' => $count]);
        // return response()->json(['result'=>'1','count'=>$count]);
    }

    public function remove_wishlist(Request $request)
    {
        $id = $request->id;
        $user = Session::get('user_id');
        $wishlist = Users::where('user_id', $user)->pluck('wishlist')->first();
        if (!empty($wishlist)) {
            $w_array = array_filter(explode(',', $wishlist));
        } else {
            $w_array = [];
        }
        if (($key = array_search($id, $w_array)) !== false) {
            unset($w_array[$key]);
        }
        // array_push($w_array,$id);
        $w_array = implode(',', $w_array);
        Users::where('user_id', $user)->update([
            'wishlist' => $w_array
        ]);
        // return '1';
        return back()->with(['success' => 'WishList Item Deleted']);
    }

    public function my_wishlist()
    {
        if (Session::has('user_id')) {
            $user = Session::get('user_id');
            $wishlist = Users::where('user_id', $user)->pluck('wishlist')->first();
            $wishlist = array_filter(explode(',', $wishlist));
            $products = Product::select(['products.*', 'brands.brand_name'])
                ->leftjoin('brands', 'brands.id', '=', 'products.brand')
                ->whereIn('products.id', $wishlist)->get();
            return Inertia::render('WishList', ['products' => $products, 'component' => "wishlist", 'wishlist' => $wishlist]);
            // return view('public.wishlists',['products'=>$products]);
        } else {
            return Inertia::render('UserLogin');
            // return redirect('user_login');
        }
    }

    public function my_cart()
    {
        if (session()->has('user_id')) {
            $token = csrf_token();
            $user_id = session()->get('user_id');
            $cart = Cart::where('product_user', $user_id)->pluck('product_id');
            $attributes = Attribute::select('*')->get();
            $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
                ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
                ->get();
            $color = Color::select(['colors.*'])->get();
            $cities = City::select(['cities.*'])->get();
            // if(empty($cart)){
            //     $products = [];
            // }else{
            $products =  Cart::select(['cart.*', 'cart.id as cart_id', 'products.*', 'colors.color_code'])
                ->leftjoin('products', 'products.id', '=', 'cart.product_id')
                ->leftjoin('colors', 'colors.id', '=', 'cart.color')
                ->where('product_user', $user_id)
                ->whereIn('cart.product_id', $cart)->get();
            // }

            // foreach($products as $product){
            //     $price = get_product_price($product->id);      
            //     $product->discount = $price->old_price - $price->new_price;    
            //     $product->discount_percent = $price->discount; 
            // } 
            return Inertia::render('Cart', ['attrvalues' => $attrvalues, 'attributes' => $attributes, 'products' => $products, 'color' => $color, 'cart' => $cart, 'city' => $cities, 'token' => $token]);
            // return view('public.cart',['attrvalues'=>$attrvalues,'attributes'=>$attributes,'products'=>$products,'color'=>$color]);
        } else {
            //return view('public.local-cart');
            return Inertia::render('UserLogin');
        }
    }

    public function show_local_cart(Request $request)
    {
        $attributes = Attribute::select('*')->get();
        $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
            ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
            ->get();
        $color = Color::select(['colors.*'])->get();

        $products =  Product::whereIn('id', $request->product_id)->get();
        return view('public.partials.show-local-cart', ['attrvalues' => $attrvalues, 'attributes' => $attributes, 'products' => $products, 'color' => $color]);
    }

    public function update_cart_qty(Request $request)
    {
        Cart::where('id', $request->id)->update([
            'qty' => $request->qty
        ]);
    }



    public function save_cart(Request $request)
    {
        //   return $request->input();
        $product_id = $request->product_id;
        $color_id = '';
        if ($request->color && $request->color != '') {
            $color_id = $request->color;
        }
        $attr_array = [];
        foreach ($request->input() as $key => $value) {
            if ($key != 'product_id' && $key != 'color' && $key != 'location') {
                $attr_key = Attribute::where('title', ucfirst($key))->pluck('id')->first();
                array_push($attr_array, "{$attr_key}:{$value}");
            }
        }
        $attrvalues = implode(',', $attr_array);
        // return $attr_array;
        if (session()->has('user_name')) {
            $user_id = session()->get('user_id');
            $city = $request->location;
            $state = City::where('id', $city)->pluck('state')->first();
            $country = State::where('id', $state)->pluck('country')->first();
            Users::where('user_id', $user_id)->update([
                'state' => $state,
                'city' => $city,
                'country' => $country,
            ]);
            $request->session()->put('user_city', $city);
            $cart = new Cart();
            $cart->product_id = $product_id;
            $cart->product_user = $user_id;
            $cart->attrvalues = $attrvalues;
            $cart->color = $color_id;
            $result = $cart->save();

            return back()->with(['success' => 'Added Successfully.']);
        } else {
            return Inertia::location(url('user_login'));
        }
        // }

    }

    public function remove_cart(Request $request)
    {
        $product_id = $request->id;
        if (session()->has('user_name')) {
            $destroy = Cart::where(['product_id' => $product_id])->delete();
            return back()->with(['success' => 'Product Deleted Successful Cart.']);
            // return $destroy;
        } else {
            return back()->with(['error' => 'Product Not Deleted Cart.']);
            //return false;
        }
    }

    public function checkout(Request $request)
    {
        // return $request->input();
        if (session()->has('user_id')) {
            $user_id = session()->get('user_id');
            $user = Users::select(['users.name', 'users.phone', 'users.city', 'users.address', 'users.state', 'users.pin_code', 'users.country', 'countries.country_name', 'states.state_name', 'cities.city_name'])
                ->leftJoin('countries', 'countries.id', '=', 'users.country')
                ->leftJoin('states', 'states.id', '=', 'users.state')
                ->leftJoin('cities', 'cities.id', '=', 'users.city')
                ->where(['user_id' => $user_id])->first();
            // return $user;
            $attributes = Attribute::select('*')->get();
            $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
                ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
                ->get();

            if ($request->product_id && $request->product_id != '') {
                $products = Product::where('id', $request->product_id)->get();
                foreach ($products as $product) {
                    $product->qty = 1;
                    if ($request->color && $request->color != '') {
                        $product->color_code = Color::where('id', $request->color)->pluck('color_code')->first();
                    }
                }
                Session::put('checkout', 'checkout');
                Session::put('order', $request->input());
            } else {
                $products =  Cart::select(['cart.*', 'cart.id as cart_id', 'products.*', 'colors.color_code'])
                    ->leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->leftjoin('colors', 'colors.id', '=', 'cart.color')
                    ->where('product_user', $user_id)->get();
            }


            $colors = Color::select(['colors.*'])->get();

            $payment_method = PaymentMethod::select(['paymentmethod.*'])->get();

            $countries = Country::select(['countries.*'])->get();

            $states = State::select(['states.*'])->get();

            $cities = City::select(['cities.*'])->get();

            $razorkey = env('RAZOR_KEY');
            return Inertia::render('CheckOut', ['user' => $user, 'products' => $products, 'attributes' => $attributes, 'attrvalues' => $attrvalues, 'colors' => $colors, 'payment_method' => $payment_method, 'countries' => $countries, 'states' => $states, 'cities' => $cities, 'razorkey' => $razorkey]);
            // return view('public.checkout',['user'=>$user,'products'=>$products,'attributes'=>$attributes,'attrvalues'=>$attrvalues,'colors'=>$colors,'payment_method'=>$payment_method,'country'=>$country,'state'=>$state,'city'=>$city]);
        } else {
            return Inertia::render('UserLogin');
        }
    }

    public function my_orders(Request $request)
    {
        if (session()->has('user_name')) {
            $user = session()->get('user_id');
            if ($request->input()) {
                $order_products = OrderProducts::select(['order_products.*', 'products.product_name', 'products.thumbnail_img', 'products.shipping_days'])
                    ->leftJoin('products', 'products.id', '=', 'order_products.product_id')
                    ->where('order_id', $request->id)->get();
                $order_detail = Order::find($request->id);
            } else {
                $order_products = [];
                $order_detail = [];
            }
            $my_orders = Order::select(['orders.*', \DB::raw('GROUP_CONCAT(products.product_name SEPARATOR "|||") as names')])
                ->leftJoin('order_products', 'order_products.order_id', '=', 'orders.id')
                ->leftJoin('products', 'products.id', '=', 'order_products.product_id')
                ->where('user', $user)->orderBy('id', 'DESC')
                ->groupBy('orders.id')
                ->get();
            $attributes = Attribute::select('*')->get();
            $attrvalues = Attrvalue::select(['attrvalues.*', 'attributes.title'])
                ->leftjoin('attributes', 'attributes.id', '=', 'attrvalues.attribute')
                ->get();
            $color = Color::select(['colors.*'])->get();

            $reviews = Review::where('user', session()->get('user_id'))->pluck('product')->all();
            return Inertia::render('MyOrder', ['my_orders' => $my_orders, 'order_detail' => $order_detail, 'order_products' => $order_products, 'attributes' => $attributes, 'attrvalues' => $attrvalues, 'color' => $color, 'reviews' => $reviews]);
        } else {
            return Inertia::render('UserLogin');
        }
    }

    public function show_order_products(Request $request)
    {
        $user = session()->get('user_id');
        $products = OrderProducts::select(['order_products.*', 'products.product_name', 'products.thumbnail_img', 'products.shipping_days'])
            ->leftJoin('products', 'products.id', '=', 'order_products.product_id')
            ->where('order_id', $request->id)->get();
        // $attributes = Attribute::select('*')->get();
        // $attrvalues = Attrvalue::select(['attrvalues.*','attributes.title'])
        //  ->leftjoin('attributes','attributes.id','=','attrvalues.attribute')
        //  ->get();
        // $color = Color::select(['colors.*'])->get();

        $order = Order::find($request->id);
        // return $products;
        // return Inertia::render('OrderProducts',['result'=>['products' =>$products,'attributes'=>$attributes,'attrvalues'=>$attrvalues,'colors'=>$color,'order'=>$order]]);
        // return Inertia::render('OrderProducts',['products' =>$products,'attributes'=>$attributes,'attrvalues'=>$attrvalues,'colors'=>$color,'order'=>$order]);

        // return view('public.partials.order-products',['products'=>$products,'attributes'=>$attributes,'attrvalues'=>$attrvalues,'colors'=>$color,'order'=>$order]);
    }

    public function get_state(Request $request)
    {
        if ($request->input()) {
            $country_id = $request->country_id;

            $states = State::where(['country' => $country_id])->get();

            $output = '<option disabled selected value="">Select State Value</option>';
            if (!empty($states)) {
                foreach ($states as $row) {
                    $output .= '<option value="' . $row['id'] . '" data-state="' . $row['id'] . '">' . $row['state_name'] . '</option>';
                }
            } else {
                $output = '<option disabled selected value=">No State Value Found</option>';
            }
            return back()->with(['output' => $output]);
            // return $output;
        }
    }

    public function get_city(Request $request)
    {
        if ($request->input()) {
            $state_id = $request->state_id;

            $cities = City::where(['state' => $state_id])->get();

            $output = '<option disabled selected value="">Select State Value</option>';
            if (!empty($cities)) {
                foreach ($cities as $row) {
                    $output .= '<option value="' . $row['id'] . '">' . $row['city_name'] . '</option>';
                }
            } else {
                $output = '<option disabled selected value=">No City Value Found</option>';
            }
            return back()->with(['output' => $output]);
            // return $output;

        }
    }

    public function change_address(Request $request)
    {
        if (session()->has('user_id')) {
            $user_id = session()->get('user_id');
            Users::where('user_id', $user_id)->update([
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city
            ]);
            return '1';
        }
    }

    public function my_reviews()
    {
        Paginator::useBootstrap();
        if (Session::has('user_id')) {
            $user = session()->get('user_id');
            $reviews = Review::select(['reviews.*', 'products.product_name'])
                ->where('user', $user)
                ->leftJoin('products', 'products.id', '=', 'reviews.product')
                ->paginate(10);
            return Inertia::render('MyReviews', ['reviews' => $reviews]);
        } else {
            return Inertia::render('UserLogin');
        }
    }

    // show forgot password page
    public function forgotPassword_show()
    {
        if (session()->has('user_id')) {
            return Inertia::location('/MyProfile');
        } else {
            return Inertia::render('ForgotPassword');
        }
    }

    // check email and send email 
    public function forgotPassword_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = Users::where('email', $request->email)->first();
        if ($user->status == '0') {
            return back()->with('error', 'Your Account is blocked by Site Administrator');
        }

        $token = Str::random(40);
        $domain = URL::to('/');
        $url = $domain . '/reset-password?token=' . $token;

        $data['url'] = $url;
        $data['user_name'] = $user->name;
        $data['user_email'] = $request->email;
        $data['title'] = 'Password Reset';
        $data['body'] = 'Please click on below link to reset you password.';
        try {

            Mail::send('public.email.forgotPassword', ['data' => $data], function ($message) use ($data) {
                $message->to($data['user_email'])->subject($data['title']);
            });
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            return back()->with('success', 'Please check your email to reset your password');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // show reset password page
    public function resetPassword_show(Request $request)
    {
        $token = $request->token;
        if (!Session::has('user_id')) {
            $email = DB::table('password_resets')->where(['token' => $token])->first();
            if ($email) {
                return Inertia::render('ResetPassword', ['token' => $token, 'email' => $email]);
            } else {
                return abort('404');
            }
        } else {
            return redirect('/');
        }
    }


    // update password
    public function submitResetPasswordForm(Request $request)
    {

        if (!Session::has('user_id')) {
            $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            $updatePassword = DB::table('password_resets')
                ->where(['email' => $request->email, 'token' => $request->token])
                ->first();

            if (!$updatePassword) {
                return back()->with('error', 'Invalid token!');
            }

            // return $request->token;
            $user = Users::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email' => $request->email])->delete();

            return Inertia::render('NewPassword');
            // return back()->with(['success'=>'Your password has been changed! Login with new password']);
            //return redirect('UserLogin')->with('success', 'Your password has been changed! Login with new password');
        } else {
            return abort('404');
        }
    }
}
