<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Sticker extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'image',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Sticker "%s" was %s.', basename($this->image), $event);
    }

    public function section()
    {
        return $this->belongsTo(StickerSection::class, 'section_id');
    }
}
