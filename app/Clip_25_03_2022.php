<?php

namespace App;

use BeyondCode\Comments\Traits\HasComments;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Overtrue\LaravelLike\Traits\Likeable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class Clip extends Model implements Viewable
{
    use Favoriteable, HasComments, HasTags, InteractsWithViews, Likeable, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        'approved' => 'boolean',
        'private' => 'boolean',
        'comments' => 'boolean',
    ];

    protected $fillable = [
        'video', 'screenshot', 'preview', 'description', 'language', 'private', 'comments', 'duration', 'approved',
        'location', 'latitude', 'longitude',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Clip #%d was %s.', $this->id, $event);
    }

    public function getDescriptionShortAttribute()
    {
        if (mb_strlen($this->description) > 20) {
            return mb_substr($this->description, 0, 20) . '…';
        }
        return $this->description;
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'subject');
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function sections()
    {
        return $this->belongsToMany(ClipSection::class,
            'clip_section_clips',
            'clip_id',
            'section_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
