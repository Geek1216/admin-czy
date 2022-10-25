<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Song extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'title', 'artist', 'album', 'audio', 'cover', 'duration', 'details',
    ];

    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Song "%s" was %s.', Str::lower($this->title), $event);
    }

    public function sections()
    {
        return $this->belongsToMany(
            SongSection::class,
            'song_section_songs',
            'song_id',
            'section_id'
        );
    }
}
