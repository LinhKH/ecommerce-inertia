<?php

namespace App\PaymentGateway;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

/**
 * Class Paypal
 * @package App
 */
class Paypal
{
    public $config;
    public $provider;
    public function __construct()
    {
        $config = $this->setPaypalConfig();
        $this->provider = new PayPalClient($config);
        $this->provider->getAccessToken();
    }

    function setPaypalConfig(): array
    {
        $config = [
            'mode'    => env('PAYPAL_MODE', ''), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => env('PAYPAL_CLIENT_ID', ''),
                'client_secret'     => env('PAYPAL_SECRET', ''),
                'app_id'            => date('Ymd') . rand(1, 10),
            ],
            'live' => [
                'client_id'         => env('PAYPAL_CLIENT_ID', ''),
                'client_secret'     => env('PAYPAL_SECRET', ''),
                'app_id'            => date('Ymd') . rand(1, 10),
            ],

            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => env('PAYPAL_CURRENCY', 'USD'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];

        return $config;
    }

    function capturePaymentOrder($token)
    {
        return $this->provider->capturePaymentOrder($token);
    }


    public function checkout()
    {
        /** calculate payable amount */
        $grandTotal = session()->get('amount');
        $payableAmount = round($grandTotal);

        $response = $this->provider->createOrder([
            'intent' => "CAPTURE",
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('payment.cancel')
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $payableAmount
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != NULL) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']['message']]);
        }
    }
}
