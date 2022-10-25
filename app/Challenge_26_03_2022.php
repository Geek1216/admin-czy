<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Challenge extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'hashtag', 'image', 'description',
    ];

    public function clips()
    {
        return Clip::query()->withAnyTags((array)$this->hashtag, 'hashtags');
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Challenge "%s" was %s.', Str::lower($this->hashtag), $event);
    }
}
