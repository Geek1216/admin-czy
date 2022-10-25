<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\Intl\Currencies;

class Credit extends JsonResource
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
            // $code = setting('payment_currency', config('fixtures.payment_currency'));
            $code = "INR";
            $symbol = $code === 'BTC' ? '₿' : Currencies::getSymbol($code);
            $currency = $symbol !== $code ? $symbol : $code;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $code === 'BTC'
                ? sprintf('%s%f', $currency, (float) $this->price)
                : sprintf('%s%.2f', $currency, $this->price / 100),
            'value' => $this->value,
            'play_store_product_id' => $this->play_store_product_id,
            //'created_at' => $this->created_at->toIso8601String(),
            //'updated_at' => $this->updated_at->toIso8601String(),
			'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
