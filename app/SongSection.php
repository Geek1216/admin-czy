<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class SongSection extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'order',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Song section "%s" was %s.', Str::lower($this->name), $event);
    }

    public function songs()
    {
        return $this->belongsToMany(
            Song::class,
            'song_section_songs',
            'section_id',
            'song_id'
        );
    }
}
