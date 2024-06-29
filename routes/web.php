<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttrvaluesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentmethodController;
use App\Http\Controllers\FlashdealController;
use App\Http\Controllers\ReviewController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::post('/admin', [AdminController::class, 'index']);
Route::group(['middleware' => ['protectedPage']], function () {
    Route::get('admin', [AdminController::class, 'index']);
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('admin/logout', [AdminController::class, 'logout']);
    Route::any('admin/general-settings', [SettingsController::class, 'general_settings']);
    Route::any('admin/profile-settings', [SettingsController::class, 'profile_settings']);
    Route::post('admin/profile-settings/change-password', [SettingsController::class, 'change_password']);
    Route::any('admin/social-settings', [SettingsController::class, 'social_settings']);
    Route::resource('admin/banner', BannerController::class);
    Route::resource('admin/brand', BrandController::class);
    Route::resource('admin/category', CategoryController::class);
    Route::resource('admin/sub-category', SubcategoryController::class);
    Route::resource('admin/products', ProductController::class);
    Route::post('admin/get-attrvalue', [ProductController::class, 'get_attrvalue']);
    Route::resource('admin/tax', TaxController::class);
    Route::resource('admin/colors', ColorController::class);
    Route::resource('admin/attribute', AttributeController::class);
    Route::resource('admin/attribute-values', AttrvaluesController::class);
    Route::resource('admin/countries', CountryController::class);
    Route::resource('admin/states', StateController::class);
    Route::resource('admin/cities', CityController::class);
    Route::resource('admin/pages', PagesController::class);
    Route::resource('admin/orders', OrderController::class);
    Route::get('admin/orders/{id}/view_order', [OrderController::class, 'view_order']);
    Route::post('admin/order-product/delivered', [OrderController::class, 'changeDelivery']);
    Route::resource('admin/users', UserController::class);
    Route::post('admin/users/block', [UserController::class, 'changeStatus']);
    Route::post('admin/page_showIn_header', [PagesController::class, 'show_in_header']);
    Route::post('admin/page_showIn_footer', [PagesController::class, 'show_in_footer']);
    Route::get('admin/product-sale', [ReportController::class, 'product_sale']);
    Route::get('admin/product-stock', [ReportController::class, 'product_stock']);
    Route::resource('admin/payment-method', PaymentmethodController::class);
    Route::post('admin/payment-method/status', [PaymentmethodController::class, 'changeStatus']);
    Route::resource('admin/flash-deals', FlashdealController::class);
    Route::post('admin/get-flash', [FlashdealController::class, 'get_flash']);
    Route::post('admin/get-flash-edit', [FlashdealController::class, 'get_flash_edit']);
    Route::get('admin/reviews/{id}/edit', [ReviewController::class, 'edit']);
    Route::put('admin/reviews/{id}', [ReviewController::class, 'update']);
    Route::post('admin/view_review', [ReviewController::class, 'show']);
    Route::post('admin/approve_review', [ReviewController::class, 'approveReview']);
    Route::post('admin/delete_review', [ReviewController::class, 'destroy']);
    Route::any('admin/reviews', [ReviewController::class, 'index']);
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/c/{text}', [HomeController::class, 'search_products'])->name('search.products');
Route::get('/product/{text}', [HomeController::class, 'productpage']);
Route::get('/flash-deals', [HomeController::class, 'allflashdeals']);
Route::get('/flash-products', [HomeController::class, 'allflashproducts']);
Route::get('/flash-products/{text}', [HomeController::class, 'flashproducts']);

Route::get('/today-deals', [HomeController::class, 'todayDeals']);

Route::get('/signup', [UserController::class, 'create']);
Route::post('/signup', [UserController::class, 'store']);

Route::get('/user_login', [UserController::class, 'login']);
Route::post('/user_login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

Route::post('/my-profile/get-state', [UserController::class, 'get_state']);
Route::post('/my-profile/get-city', [UserController::class, 'get_city']);

Route::get('/changepassword', [UserController::class, 'changepassword']);
Route::post('/changepassword', [UserController::class, 'change_password']);

Route::get('/my-profile', [UserController::class, 'my_profile']);
Route::post('/my-profile', [UserController::class, 'update']);

Route::post('/add-wishlist', [UserController::class, 'add_wishlist']);
Route::post('/remove-wishlist', [UserController::class, 'remove_wishlist']);
Route::get('/wishlists', [UserController::class, 'my_wishlist']);

Route::get('/cart', [UserController::class, 'my_cart']);
Route::post('/show_cart', [UserController::class, 'show_local_cart']);
Route::post('/save_cart', [UserController::class, 'save_cart']);
Route::post('/remove_cart', [UserController::class, 'remove_cart']);
Route::post('/update_cart_qty', [UserController::class, 'update_cart_qty']);

Route::post('/change_address', [UserController::class, 'change_address']);
Route::get('/checkout', [UserController::class, 'checkout']);
Route::post('/checkout', [UserController::class, 'order_products']);

Route::get('success', [PaymentController::class, 'success']);
// Route::get('pay-with-paypal/{amount}', [PaymentController::class, 'payWithpaypal']);
Route::get('pay-with-cod/{amount}', [PaymentController::class, 'payWithCod']);
Route::get('pay-with-paypal/{amount}', [PaymentController::class, 'payWithpaypalCustomize']);
Route::get('/paypal/status', [PaymentController::class, 'getPaymentStatus'])->name('paypal-status');
Route::get('checkout/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('checkout/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('checkout/payment/failed', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('/pay-with-razorpay/{id}/{text}', [PaymentController::class, 'yb_payWithRazorpay']);

Route::get('/my_orders', [UserController::class, 'my_orders']);
Route::post('/my_orders', [UserController::class, 'my_orders']);

Route::post('get-suggestions', [HomeController::class, 'get_suggestions']);
Route::get('/all-products', [HomeController::class, 'search_products'])->name('search.products');
Route::get('search', [HomeController::class, 'search_products'])->name('search.products');

Route::get('review/create/{id}', [ReviewController::class, 'create']);
Route::post('review/store', [ReviewController::class, 'store']);
Route::get('my-reviews', [UserController::class, 'my_reviews']);

Route::get('forgot-password', [UserController::class, 'forgotPassword_show']);
Route::post('forgot-password', [UserController::class, 'forgotPassword_submit']);
Route::get('reset-password', [UserController::class, 'resetPassword_show']);
Route::post('reset-password', [UserController::class, 'submitResetPasswordForm']);
Route::get('{page}', [HomeController::class, 'site_pages']);
