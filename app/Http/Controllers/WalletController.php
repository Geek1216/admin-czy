<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Http\Resources\Gift as GiftResource;
use App\Http\Resources\Redemption as RedemptionResource;
use App\Item;
use App\Jobs\SendNotification;
use App\Payment;
use App\User;
use Bavix\Wallet\Models\Transfer;
use Bavix\Wallet\Objects\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Bavix\Wallet\Models\Wallet;


class WalletController extends Controller
{
    use PaymentTrait;

public function balance(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        /** @var User $user */
        $user = $request->user();
        $wallet = Wallet::query()
            ->where('holder_id', $user['id'])->first();
        // $user->wallet->refreshBalance();
        return JsonResource::make([
            'balance' => is_null($wallet) ? 0 : $wallet['balance'], //$user->balance,
        ]);
    }

    public function gift(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $data = $this->validate($request, [
            'to' => ['required', 'integer', 'exists:users,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);
        /** @var User $sender */
        $sender = $request->user();
        /** @var User $receiver */
        $receiver = User::findOrFail($data['to']);
        $cart = app(Cart::class);
        foreach ($data['items'] as $datum) {
            $item = Item::findOrFail($datum['id']);
            $cart->addItem($item, $datum['quantity']);
        }

        if ($sender->balance >= ($total = $cart->getTotal($sender))) {
            $sender->transfer($receiver, $total, ['title' => 'Gift sent.']);
            $receiver->payCart($cart);
            SendNotification::dispatch(
                __('notifications.sent_you_gifts.title', ['user' => $sender->username]),
                __('notifications.sent_you_gifts.body'),
                null,
                $receiver
            );
            $sender->wallet->refreshBalance();
            return JsonResource::make([
                'balance' => $sender->balance,
            ]);
        }

        throw ValidationException::withMessages([
            'items' => __('You do not have enough balance.'),
        ]);
    }

    public function gifts(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        return GiftResource::make($request->user());
    }

    public function recharge(Request $request)
    {
        // dd($request->all());die;
        //abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $data = $this->validate($request, [
            'credit' => ['required', 'integer', 'exists:credits,id'],
        ]);
        $credit = Credit::findOrFail($data['credit']);
        $reference = Str::uuid()->toString();
        $gateway = $this->createPaymentRequest($request, $reference, $credit->price);
        if (empty($gateway)) {
            throw ValidationException::withMessages([
                'credit' => __('Could not initiate wallet recharge due to mis-configured gateway.'),
            ]);
        }

        /** @var User $user */
        $user = $request->user();
        $payment = $user->payments()->create([
            'reference' => $reference,
            'amount' => $credit->price,
            'data' => $gateway + ['credit_id' => $credit->id],
            'status' => 'pending',
        ]);
        return JsonResource::make([
            'redirect' => route('wallet.redirect', ['payment' => $payment->reference])
        ]);
    }

    public function rechargeIab(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $data = $this->validate($request, [
            'sku' => ['required', 'string', 'max:255'],
            'token' => ['required', 'string', 'max:1024'],
        ]);
        $credit = Credit::query()
            ->where('play_store_product_id', $data['sku'])
            ->firstOrFail();
        /** @var \Google_Service_AndroidPublisher $service */
        $service = app('google_play_service');
        // $purchase = $service->purchases_products->get(
        //     setting('play_store_package_name', config('services.google_play.package_name')), $data['sku'], $data['token']);
        if ($purchase->getPurchaseState() !== 0 || $purchase->getConsumptionState() !== 0) {
            throw ValidationException::withMessages([
                'token' => __('This purchase token in invalid.'),
            ]);
        }

        /** @var User $user */
        $user = $request->user();
        $payment = $user->payments()->create([
            'reference' => Str::uuid()->toString(),
            'amount' => $credit->price,
            'data' => [
                'gateway' => 'play_store',
                'play_store_product_id' => $credit->play_store_product_id,
                'credit_id' => $credit->id,
            ],
            'status' => 'successful',
        ]);
        $user->deposit($credit->value);
        SendNotification::dispatch(
            __('notifications.wallet_recharge_successful.title'),
            __('notifications.wallet_recharge_successful.body', [
                // 'currency' => setting('payment_currency', config('fixtures.payment_currency')),
                'amount' => $payment->amount / 100,
            ]),
            null,
            $user
        );
        $user->wallet->refreshBalance();
        return JsonResource::make([
            'balance' => $user->balance,
        ]);
    }

    public function redeem(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $data = $this->validate($request, [
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'integer', 'exists:items,id'],
        ]);
        /** @var User $user */
        $user = $request->user();
        if (empty($user->redemption_mode) || empty($user->redemption_address)) {
            throw ValidationException::withMessages([
                'items' => __('You have not set a redemption mode in Profile yet.'),
            ]);
        }
        $cart = app(Cart::class);
        foreach ($data['items'] as $id) {
            /** @var Item $item */
            $item = Item::findOrFail($id);
            $quantity = $user->transfers()
                ->where('to_type', $item->getMorphClass())
                ->where('to_id', $item->getKey())
                ->where('status', Transfer::STATUS_PAID)
                ->count();
            if ($quantity >= $item->minimum) {
                $cart->addItem($item, $quantity);
            }
        }
        $total = $cart->getTotal($user);
        if ($total <= 0) {
            throw ValidationException::withMessages([
                'items' => __('You do not have enough gifts to redeem yet.'),
            ]);
        }

        $user->refundCart($cart);
        $user->withdraw($total, ['title' => 'Redeemed gifts.']);
        $amount = 0;
        foreach ($cart->getUniqueItems() as $item) {
            $quantity = $cart->getQuantity($item);
            $amount += ($item->value * $quantity);
        }

        $user->redemptions()->create([
            'amount' => $amount,
            'mode' => $user->redemption_mode,
            'address' => $user->redemption_address,
            'status' => 'pending',
        ]);
        return GiftResource::make($user);
    }

    public function redemptions(Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        /** @var User $user */
        $user = $request->user();
        $redemptions = $user->redemptions()->latest()->paginate();
        return RedemptionResource::collection($redemptions);
    }

    public function redirect(string $payment)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        $payment = Payment::query()
            ->where('reference', $payment)
            ->where('status', 'pending')
            ->firstOrFail();
        if ($payment->data['gateway'] === 'bitpay') {
            return redirect()->to($payment->data['invoice_url']);
        } else if ($payment->data['gateway'] === 'instamojo') {
            return redirect()->to($payment->data['payment_url']);
        } else if ($payment->data['gateway'] === 'paypal') {
            return redirect()->to($payment->data['links'][1]['href']);
        } else if ($payment->data['gateway'] === 'razorpay') {
            return view('payment.razorpay', $payment->data);
        } else if ($payment->data['gateway'] === 'stripe') {
            return view('payment.stripe', $payment->data);
        }

        abort(404);
    }

    public function response(string $payment, Request $request)
    {
        // abort_if(!setting('gifts_enabled', config('fixtures.gifts_enabled')), 404);
        /** @var Payment $payment */
        $payment = Payment::query()
            ->where('reference', $payment)
            ->where('status', 'pending')
            ->firstOrFail();
        $status = $this->checkPaymentStatus($request, $payment);
        if ($status !== false) {
            $payment->status = 'successful';
            $payment->data = $payment->data + $status;
            $payment->save();
            $credit = Credit::findOrfail($payment->data['credit_id']);
            $payment->user->deposit($credit->value);
            SendNotification::dispatch(
                __('notifications.wallet_recharge_successful.title'),
                __('notifications.wallet_recharge_successful.body', [
                    //'currency' => setting('payment_currency', config('fixtures.payment_currency')),
                    'amount' => $payment->amount / 100,
                ]),
                null,
                $payment->user
            );
        } else {
            $payment->status = 'failed';
            $payment->save();
        }

        return __('Purchase flow completed. You may now close this window.');
    }

    public function setting($key = null, $default = null)
    {
        $setting = app('setting');

        if (is_null($key)) {
            return $setting;
        }

        if (is_array($key)) {
            $setting->set($key);

            return $setting;
        }

        return $setting->get($key, $default);
    }
}
