<?php

namespace App;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model implements Product
{
    use HasWallet, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'image', 'price', 'value', 'minimum',
    ];

    /**
     * @param Customer $customer
     * @param int $quantity
     * @param bool $force
     *
     * @return bool
     */
    public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool
    {
        return true;
    }

    public function getAmountProduct(Customer $customer)
    {
        return $this->price;
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Item "%s" was %s.', $this->name, $event);
    }

    /**
     * @return array
     */
    public function getMetaProduct(): ?array
    {
        return ['title' => $this->name];
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return (string)$this->getKey();
    }
}
