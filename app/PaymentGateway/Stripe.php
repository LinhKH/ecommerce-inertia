<?php

namespace App\PaymentGateway;

use Stripe\Exception\CardException;
use Stripe\StripeClient;

use Stripe\Stripe as StripeTest;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Validator;

/**
 * Class Stripe
 * @package App
 */
class Stripe
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = StripeTest::setApiKey('sk_test_51NhSkBLAjq0ac3amThHW5zGEFluTCqirQw3xYwNh2KlYTIoV1nUfj0wXQ1ktXOlooaVRF0M99N7XXnaTBoLX7xgd00suPQpGgI');
    }

    public function create()
    {
        /** calculate payable amount */
        $grandTotal = session()->get('grand_total');
        $payableAmount = round($grandTotal) * 100;

        $response = StripeSession::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Product'
                        ],
                        'unit_amount' => $payableAmount
                    ],
                    'quantity' => 1
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel')
        ]);

        return redirect()->away($response->url);
    }

    public function retrieve($sessionId)
    {
        return StripeSession::retrieve($sessionId);
    }
}
