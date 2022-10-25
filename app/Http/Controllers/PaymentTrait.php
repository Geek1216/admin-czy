<?php

namespace App\Http\Controllers;

use App\Payment;
use BitPaySDKLight\Client as BitPay;
use BitPaySDKLight\Model\Invoice\Invoice;
use Illuminate\Http\Request;
use Instamojo\Instamojo;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Razorpay\Api\Api as Razorpay;
use Stripe\Checkout\Session;

trait PaymentTrait
{
    public function checkPaymentStatus(Request $request, Payment $payment)
    {
        if ($payment->data['gateway'] === 'bitpay') {
            $bitpay = app(BitPay::class);
            $invoice = $bitpay->getInvoice($payment->data['invoice_id']);
            if ($invoice->getStatus() === 'paid') {
                return ['token' => $invoice->getToken()];
            }
        } else if ($payment->data['gateway'] === 'instamojo') {
            $payment_id = $request->get('payment_id');
            $payment_request_id = $request->get('payment_request_id');
            $payment_status = $request->get('payment_status');
            if ($payment_id && $payment_request_id && $payment->data['payment_request_id'] === $payment_request_id && $payment_status === 'Credit') {
                $instamojo = app(Instamojo::class);
                $response = $instamojo->getPaymentRequestDetails($payment_request_id);
                if ($response['status'] === 'Completed' && count($response['payments'])) {
                    $payment_id_2 = basename($response['payments'][0]);
                    if ($payment_id === $payment_id_2) {
                        $response = $instamojo->getPaymentDetails($payment_id);
                        if ($response['status'] === true) {
                            return compact('payment_id');
                        }
                    }
                }
            }
        } else if ($payment->data['gateway'] === 'paypal') {
            if ($payer_id = $request->get('PayerID')) {
                $request = new OrdersCaptureRequest($payment->data['order_id']);
                $request->prefer('return=representation');
                $paypal = app(PayPalHttpClient::class);
                $response = $paypal->execute($request);
                if ($response->statusCode === 201) {
                    $success = $response->result->status === 'COMPLETED'
                        && $response->result->payer->payer_id === $payer_id;
                    if ($success) {
                        return compact('payer_id');
                    }
                }
            }
        } else if ($payment->data['gateway'] === 'razorpay') {
            $razorpay_payment_id = $request->get('razorpay_payment_id');
            $razorpay_order_id = $request->get('razorpay_order_id');
            $razorpay_signature = $request->get('razorpay_signature');
            if ($razorpay_payment_id && $razorpay_order_id && $razorpay_signature) {
                /** @var Razorpay $razorpay */
                $razorpay = app(Razorpay::class);
                $payment = $razorpay->payment->fetch($razorpay_payment_id);
                $success = ($payment->order_id === $razorpay_order_id) && ($payment->status === 'captured');
                if ($success) {
                    return ['payment_id' => $razorpay_payment_id];
                }
            }
        } else if ($payment->data['gateway'] === 'stripe') {
            $session = Session::retrieve($payment->data['session_id']);
            if ($session->payment_status === 'paid') {
                return ['payment_intent' => $session->payment_intent];
            }
        }

        return false;
    }

    public function createPaymentRequest(Request $request, string $reference, int $amount)
    {
        $gateway = setting('payment_gateway', config('fixtures.payment_gateway'));
        // $gateway = "instamojo";
        if ($gateway === 'bitpay') {
            $bitpay = app(BitPay::class);
            $invoice = new Invoice($amount, 'BTC');
            $invoice->setItemDesc(__('Recharge'));
            $invoice->setRedirectURL(route('wallet.response', ['payment' => $reference]));
            $invoice = $bitpay->createInvoice($invoice);
            return [
                'gateway' => 'bitpay',
                'invoice_id' => $invoice->getId(),
                'invoice_url' => $invoice->getUrl(),
            ];
        } else if ($gateway === 'instamojo') {
            $instamojo = app(Instamojo::class);
            $response = $instamojo->createPaymentRequest([
                'allow_repeated_payments' => false,
                'amount' => $amount / 100,
                'purpose' => __('Recharge'),
                'redirect_url' => route('wallet.response', ['payment' => $reference]),
            ]);
            return [
                'gateway' => 'instamojo',
                'payment_request_id' => $response['id'],
                'payment_url' => $response['longurl'],
            ];
        } else if ($gateway === 'paypal') {
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $reference,
                    'amount' => [
                        'value' => (string) $amount / 100,
                        // 'currency_code' => setting('payment_currency', config('fixtures.payment_currency'))
                    ]
                ]],
                'application_context' => [
                    'cancel_url' => route('wallet.response', ['payment' => $reference]),
                    'return_url' => route('wallet.response', ['payment' => $reference]),
                ],
            ];
            $paypal = app(PayPalHttpClient::class);
            $response = $paypal->execute($request);
            if ($response->statusCode === 201) {
                return [
                    'gateway' => 'paypal',
                    'order_id' => $response->result->id,
                    'links' => $response->result->links,
                ];
            }
        } else if ($gateway === 'razorpay') {
            /** @var Razorpay $razorpay */
            $razorpay = app(Razorpay::class);
            $order = $razorpay->order->create([
                // 'currency' => setting('payment_currency', config('fixtures.payment_currency')),
				'currency'=>'INR',
                'amount' => $amount*10,
                'payment_capture' => 1,
            ]);
            return [
                'gateway' => 'razorpay',
                'order_id' => $order->id,
                'callback_url' => route('wallet.response', ['payment' => $reference]),
                'cancel_url' => route('wallet.response', ['payment' => $reference]),
            ];
        } else if ($gateway === 'stripe') {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        // 'currency' => setting('payment_currency', config('fixtures.payment_currency')),
                        'product_data' => [
                            'name' => config('app.name'),
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('wallet.response', ['payment' => $reference]),
                'cancel_url' => route('wallet.response', ['payment' => $reference]),
            ]);
            return [
                'gateway' => 'stripe',
                'session_id' => $session->id,
            ];
        }

        return false;
    }
}
