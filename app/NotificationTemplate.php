<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationTemplate extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'title', 'body',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Notification template "%s" was %s.', Str::lower($this->title_short), $event);
    }

    public function getTitleShortAttribute()
    {
        if (mb_strlen($this->title) > 20) {
            return mb_substr($this->title, 0, 20) . '…';
        }
        return $this->title;
    }

    public function schedules()
    {
        return $this->hasMany(NotificationSchedule::class, 'template_id');
    }
}
