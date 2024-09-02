<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PayerInfo;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Input;
use Redirect;
use URL;
use App\Models\UserWallet;
use App\Models\Wallet_Transactions;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\PaymentData;
use App\Models\Users;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Attribute;
use Yajra\DataTables\DataTables;
use Razorpay\Api\Api;
use Exception;
use Inertia\Inertia;
use App\PaymentGateway\Paypal;
use PhpParser\Node\Stmt\Return_;

class PaymentController extends Controller
{
    // public function __construct()
    // {
    //     /** PayPal api context **/
    //     $paypal_conf = \Config::get('paypal');
    //     $this->_api_context = new ApiContext(
    //         new OAuthTokenCredential(
    //             $paypal_conf['client_id'],
    //             $paypal_conf['secret']
    //         )
    //     );
    //     $this->_api_context->setConfig($paypal_conf['settings']);
    // }
    public function payWithpaypal($amt, Request $request)
    {
        Session::put('order', $request->input());

        $amountToBePaid = $amt;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName('Ecommerce')
            /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($amountToBePaid);
        /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($amountToBePaid);
        $redirect_urls = new RedirectUrls();
        /** Specify return URL **/
        $redirect_urls->setReturnUrl(URL::route('paypal-status'))
            ->setCancelUrl(URL::route('paypal-status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Ecommerce');

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                //   return Redirect::route('my-wallet');
                return redirect()->back();
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                // return Redirect::route('my-wallet');
                // return redirect()->back('cart');
                return Inertia::location(url('cart'));
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        // return $payment;
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        Session::put('amount', $amt);
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }

        Session::put('error', 'Unknown error occurred');
        return redirect()->back();
        //   return Redirect::route('my-wallet');
    }

    public function payWithCod($amt, Request $request)
    {
        Session::put('order', $request->input());
        Session::put('amount', $amt);

        $store = $this->yb_store_ordering(['id' => 'cod'.rand(1,100000), 'payment_type' => 'cod']);
        if ($store == '1') {
            return redirect('checkout/payment/success')->with('payment_success', 'COD payment successful');
        }
    }

    public function payWithpaypalCustomize($amt, Request $request)
    {
        Session::put('order', $request->input());
        Session::put('amount', $amt);

        $paypal = new Paypal();
        return $paypal->checkout();
    }

    public function paypalSuccess(Request $request)
    {
        $paypal = new Paypal();

        $response = $paypal->capturePaymentOrder($request->token);
        $response['payment_type'] = 'paypal';

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $store = $this->yb_store_ordering($response);
            if ($store == '1') {
                return redirect('checkout/payment/success')->with('payment_success', $response['status']);
            }
        } else {
            return redirect('checkout/payment/failed')->with('payment_error', $response['error']);
        }
    }

    public function yb_store_ordering($response)
    {
        $request = session()->get('order');
        $payment = new PaymentData();
        $payment->amount = Session::get('amount');
        $payment->txn_id = $response['id'];
        $payment->pay_method = $response['payment_type'];
        $payment->pay_status = $response['payment_type'] == 'paypal' ? 1 : 0;
        $payment->save();
        $user_id = session()->get('user_id');
        if (Session::has('checkout')) {
            $user_products = Product::select('products.*', 'id as product_id')->where('id', $request['product_id'])->get();
            // return $request;
        } else {
            $user_products = Cart::select(['cart.*', 'products.taxable_price'])
            ->leftJoin('products', 'products.id', '=', 'cart.product_id')
            ->where('product_user', $user_id)
                ->get();
        }
        // return $user_products;
        $product_count = 0;
        $product_qty = 0;
        if (Session::has('checkout')) {
            $product_count = 1;
            $product_qty = 1;
        } else {
            foreach ($user_products as $product) {
                $product_count++;
                $product_qty = $product_qty + $product->qty;
            }
        }
        $order = new Order();
        $order->user = $user_id;
        $order->products = $product_count;
        $order->qty = $product_qty;
        $order->pay_id = $payment->id;
        $order->amount = Session::get('amount');
        $order->save();
        
        $buy_not_from_cart = Session::get('checkout'); // have 'checkout' then buy not from cart, else buy from cart

        foreach ($user_products as $product) {
            $attrvalues = '';
            $color = '';
            if ($request && !empty($buy_not_from_cart)) {
                $attr_array = [];
                foreach ($request as $key => $value) {
                    if ($key != 'product_id' && $key != 'color' && $key != 'location' && $key != 'pay_method' && $key != 'amount') {
                        $attr_key = Attribute::where('title', ucfirst($key))->pluck('id')->first();
                        array_push($attr_array, "{$attr_key}:{$value}");
                    } elseif ($key == 'color') {
                        $color = $value;
                    }
                }
                $attrvalues = implode(',', $attr_array);
            } else {
                $color = $product->color;
                $attrvalues = $product->attrvalues;
            }

            if (!$product->qty) {
                $product->qty = 1;
            }

            $order_products = new OrderProducts();
            $order_products->order_id = $order->id;
            $order_products->product_id = $product->product_id;
            $order_products->product_qty = $product->qty;
            $order_products->product_color = $color;
            $order_products->product_attr = $attrvalues;
            $order_products->product_amount = $product->taxable_price;
            $order_products->product_delivery = 0;
            $saveOrderProduct = $order_products->save();
            if (!Session::has('checkout')) {
                DB::table('cart')->where('product_user', $user_id)->where('product_id', $product->product_id)->delete();
            }
        }
        Session::forget('paypal_payment_id');
        Session::forget('amount');

        session()->flash('success', 'Order Confirmed Successfully');
        return $saveOrderProduct;
    }

    public function paymentSuccess()
    {
        return Inertia::render('Success');
    }
    public function paymentCancel()
    {
        return abort('404');
    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        /** clear the session payment ID **/
        if (empty($request->PayerID) || empty($request->token)) {
            session()->flash('error', 'Payment failed');
            return redirect()->back();
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {

            $user_id = session()->get('user_id');
            if (Session::has('checkout')) {
                $request = session()->get('order'); 
            }
            // return $request;

            $payment = new PaymentData();
            $payment->amount = Session::get('amount');
            $payment->txn_id = $payment_id;
            $payment->pay_method = 'paypal';
            $payment->pay_status = 1;
            $payment->save();
            if (Session::has('checkout')) {
                $user_products = Product::select('products.*', 'id as product_id')->where('id', $request['product_id'])->get();
                // return $request;
            } else {
                $user_products = Cart::select(['cart.*', 'products.taxable_price'])
                    ->leftJoin('products', 'products.id', '=', 'cart.product_id')
                    ->where('product_user', $user_id)
                    ->get();
            }
            // return $user_products;
            $product_count = 0;
            $product_qty = 0;
            if (Session::has('checkout')) {
                $product_count = 1;
                $product_qty = 1;
            } else {
                foreach ($user_products as $product) {
                    $product_count++;
                    $product_qty = $product_qty + $product->qty;
                }
            }


            $order = new Order();
            $order->user = $user_id;
            $order->products = $product_count;
            $order->qty = $product_qty;
            $order->pay_id = $payment->id;
            $order->amount = Session::get('amount');
            $order->save();

            foreach ($user_products as $product) {
                $attrvalues = '';
                $color = '';
                if ($request) {
                    $attr_array = [];
                    foreach ($request as $key => $value) {
                        if ($key != 'product_id' && $key != 'color' && $key != 'location' && $key != 'pay_method' && $key != 'amount') {
                            $attr_key = Attribute::where('title', ucfirst($key))->pluck('id')->first();
                            array_push($attr_array, "{$attr_key}:{$value}");
                        } elseif ($key == 'color') {
                            $color = $value;
                        }
                    }
                    $attrvalues = implode(',', $attr_array);
                } else {
                    $color = $product->color;
                    $attrvalues = $product->attrvalues;
                }

                if (!$product->qty) {
                    $product->qty = 1;
                }

                $order_products = new OrderProducts();
                $order_products->order_id = $order->id;
                $order_products->product_id = $product->product_id;
                $order_products->product_qty = $product->qty;
                $order_products->product_color = $color;
                $order_products->product_attr = $attrvalues;
                $order_products->product_amount = $product->taxable_price;
                $order_products->product_delivery = 0;
                $order_products->save();
                if (!Session::has('checkout')) {
                    DB::table('cart')->where('product_user', $user_id)->where('product_id', $product->product_id)->delete();
                }
            }
            Session::forget('paypal_payment_id');
            Session::forget('amount');

            session()->flash('success', 'Order Confirmed Successfully');
            return Inertia::location(url('success'));
        }
        session()->flash('error', 'Payment failed');
        return redirect()->back();
    }

    public function yb_payWithRazorpay($amt, $pay_id, Request $request)
    {
        // return $request->input();
        if (!Session::has('checkout')) {
            Session::put('order', $request->input());
        }
        $user_id = session()->get('user_id');
        $request = session()->get('order');
        //  return $request;
        $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        // return $pay_id;
        $payment = $api->payment->fetch($pay_id);

        if (!empty($pay_id)) {
            // return $amt;
            try {
                $response = $api->payment->fetch($pay_id)->capture(array('amount' => $payment['amount']));

                $payment = new PaymentData();
                $payment->amount = $amt;
                $payment->txn_id = $pay_id;
                $payment->pay_method = 'razorpay';
                $payment->pay_status = 1;
                $payment->save();
                if (Session::has('checkout')) {
                    $order = Session::get('order');
                    $user_products = Product::select('products.*', 'id as product_id')->where('id', $order['product_id'])->get();
                } else {
                    $user_products = Cart::select(['cart.*', 'products.taxable_price'])
                        ->leftJoin('products', 'products.id', '=', 'cart.product_id')
                        ->where('product_user', $user_id)
                        ->get();
                }


                $product_count = 0;
                $product_qty = 0;
                if (Session::has('checkout')) {
                    $product_count = 1;
                    $product_qty = 1;
                } else {
                    foreach ($user_products as $product) {
                        $product_count++;
                        $product_qty = $product_qty + $product->qty;
                    }
                }

                $order = new Order();
                $order->user = $user_id;
                $order->products = $product_count;
                $order->qty = $product_qty;
                $order->pay_id = $payment->id;
                $order->amount = $amt;
                $order->save();


                foreach ($user_products as $product) {
                    $attrvalues = '';
                    $color = '';
                    if ($request) {
                        $attr_array = [];
                        foreach ($request as $key => $value) {
                            if ($key != 'product_id' && $key != 'color' && $key != 'location' && $key != 'pay_method' && $key != 'amount') {
                                $attr_key = Attribute::where('title', ucfirst($key))->pluck('id')->first();
                                array_push($attr_array, "{$attr_key}:{$value}");
                            } elseif ($key == 'color') {
                                $color = $value;
                            }
                        }
                        $attrvalues = implode(',', $attr_array);
                    } else {
                        $color = $product->color;
                        $attrvalues = $product->attrvalues;
                    }

                    if (!$product->qty) {
                        $product->qty = 1;
                    }


                    $order_products = new OrderProducts();
                    $order_products->order_id = $order->id;
                    $order_products->product_id = $product->product_id;
                    $order_products->product_qty = $product->qty;
                    $order_products->product_color = $color;
                    $order_products->product_attr = $attrvalues;
                    $order_products->product_amount = $product->taxable_price;
                    $order_products->product_delivery = 0;
                    $order_products->save();
                    if (!Session::has('checkout')) {
                        DB::table('cart')->where('product_user', $user_id)->where('product_id', $product->product_id)->delete();
                    }
                }

                return Inertia::location(url('success'));
            } catch (Exception $e) {
                return back()->with(['error' => $e->getMessage()]);
            }
        }
    }


    public function success()
    {
        if (Session::has('order')) {
            Session::forget('order');
            Session::forget('checkout');
            return Inertia::render('Success');
        } else {
            return abort('404');
        }
    }
}
