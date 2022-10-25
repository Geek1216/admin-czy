<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\Intl\Currencies;

class Redemption extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        static $code, $currency;
        if (empty($code) || empty($currency)) {
            $code = setting('payment_currency', config('fixtures.payment_currency'));
            $symbol = $code === 'BTC' ? '₿' : Currencies::getSymbol($code);
            $currency = $symbol !== $code ? $symbol : $code;
        }

        return [
            'id' => $this->id,
            'amount' => $code === 'BTC'
                ? sprintf('%s%f', $currency, (float) $this->amount)
                : sprintf('%s%.2f', $currency, $this->amount / 100),
            'mode' => $this->mode,
            'address' => $this->address,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
