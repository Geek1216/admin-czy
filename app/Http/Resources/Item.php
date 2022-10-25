<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Intl\Currencies;

class Item extends JsonResource
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
            'name' => $this->name,
            'image' => Storage::disk(setting('filesystems_cloud', config('filesystems.cloud')))->url($this->image),
            'price' => $this->price,
            'value' => $code === 'BTC'
                    ? sprintf('%s%f', $currency, (float) $this->value)
                    : sprintf('%s%.2f', $currency, $this->value / 100),
            'minimum' => $this->minimum,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
