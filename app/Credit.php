<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class Credit extends Model
{
    use HasTags, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'title', 'description', 'value', 'price', 'order', 'play_store_product_id',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Credit "%s" was %s.', Str::lower($this->title), $event);
    }
}
