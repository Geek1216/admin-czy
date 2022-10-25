<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class StickerSection extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'order',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Sticker section "%s" was %s.', Str::lower($this->name), $event);
    }

    public function stickers()
    {
        return $this->hasMany(Sticker::class, 'section_id');
    }
}
