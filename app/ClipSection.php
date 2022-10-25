<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class ClipSection extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'order',
    ];

    public function clips()
    {
        return $this->belongsToMany(
            Clip::class,
            'clip_section_clips',
            'section_id',
            'clip_id'
        );
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Clip section "%s" was %s.', Str::lower($this->name), $event);
    }
}
