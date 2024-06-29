<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use Notification;
use App\Models\Order;
use App\Models\PaymentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

    }
    public function index()
    {
        return view('paywithpaypal');
    }
    public function payWithpaypal(Request $request)
    {
        
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->get('total_price')); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('total_price'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $return_url = '/checkout?total_price='.$request->total_price.'&total_qty='.$request->total_qty.'&product_id='.$request->product_id;
        // $redirect_urls->setReturnUrl(url($return_url)) /** Specify return URL **/
        //     ->setCancelUrl(url($return_url));
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('/'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        // dd($payment->create($this->_api_context));exit;
        try {

            $payment =  $payment->create($this->_api_context);
            // return $payment;
            
            $order = new Order();
            $order->product_id = $request->product_id;
            $order->product_qty = $request->total_qty;
            $order->total_amount = $request->total_price;
            $order->product_user = session('user_id');
            $order->color_code = $request->color_code;
            $order->attrvalues = $request->attrvalue;
            $order->order_date = date('Y-m-d');
            $order->pay_method = 'paypal';
            $order->pay_req_id = $payment->id;
            $order->payment_status = '0';
            $order->delivery = '0';
            $order->shipping_price = $request->shipping_price;
            $order->save();
            
            $user = new PaymentData();
            $user->product_id = $request->product_id;
            $user->txn_id = $payment->id;
            $user->total_amount = $request->total_price;
            $user->payment_done = '0';
            $user->payment_method = 'paypal';
            $user->save();

            Session::put('order', $order->product_id);


        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error', 'Connection timeout');
                return Redirect::to('/payment-failed');

            } else {

                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::to('/payment-failed');

            }

        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;

            }

        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            /** redirect to paypal **/
            return Redirect::away($redirect_url);

        }

        Session::put('error', 'Unknown error occurred');
        return Redirect::to('/payment-failed');

    }

    public function getPaymentStatus()
    {
        $request=request();//try get from method
        // return $request;
        $payment_id = Session::get('paypal_payment_id');
        if($payment_id != ''){
            /** Get the payment ID before session clear **/
            
            /** clear the session payment ID **/
            Session::forget('paypal_payment_id');
            //if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            if (empty($request->PayerID) || empty($request->token)) {

                Session::put('error', 'Payment failed');
                return Redirect::to('/payment-failed');

            }

            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            //$execution->setPayerId(Input::get('PayerID'));
            $execution->setPayerId($request->PayerID);

            /**Execute the payment **/
            $result = $payment->execute($execution, $this->_api_context);

            if ($result->getState() == 'approved') {


                Order::where('product_id',session()->get('order'))->update([
                    'payment_status' => '1'
                ]);

                PaymentData::where('product_id',session()->get('order'))->update([
                    'payment_done' => '1'
                ]);


                Session::put('success', 'Payment success');
                //add update record for cart
                return redirect('success');  //back to product page
            }

            Session::put('error', 'Payment failed');
            return Redirect::to('/payment-failed'); 

        }else{
            return Redirect::to('/payment-failed'); 
        }
    }

    public function payment_failed(){
        return view('failed');
    }

}
