<?php

namespace App;

use BeyondCode\Comments\Comment as Base;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class Comment extends Base
{
    use HasTags, LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function getCommentShortAttribute()
    {
        if (mb_strlen($this->comment) > 20) {
            return mb_substr($this->comment, 0, 20) . '…';
        }
        return $this->comment;
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Comment "%s" was %s.', Str::lower($this->comment_short), $event);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'subject');
    }
}
