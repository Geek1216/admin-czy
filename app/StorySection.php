<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class StorySection extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'order',
    ];

    public function stories()
    {
        return $this->belongsToMany(
            Story::class,
            'story_section_stories',
            'section_id',
            'story_id'
        );
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Story section "%s" was %s.', Str::lower($this->name), $event);
    }
}
