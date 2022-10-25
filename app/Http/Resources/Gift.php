<?php

namespace App\Http\Resources;

use App\Item as ItemModel;
use Bavix\Wallet\Models\Transfer;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\Intl\Currencies;

class Gift extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        static $currency;
        if (empty($currency)) {
            // $code = setting('payment_currency', config('fixtures.payment_currency'));
            $code = "INR";
            $symbol ="INR";// Currencies::getSymbol($code);
            $currency = $symbol !== $code ? $symbol : $code;
        }

        $data = [];
        /** @var \App\User $this */
        /** @var ItemModel $item */
        foreach (ItemModel::get() as $item) {
            $transfers = $this->transfers()
                ->where('to_type', $item->getMorphClass())
                ->where('to_id', $item->getKey())
                ->where('status', Transfer::STATUS_PAID)
                ->count();
            $data[] = [
                'item' => Item::make($item),
                'balance' => $transfers,
                'value' => sprintf('%s%.2f', $currency, ($item->value * $transfers) / 100),
            ];
        }
        return $data;
    }
}
