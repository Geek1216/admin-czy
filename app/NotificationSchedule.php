<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationSchedule extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'template_id', 'time', 'clip', 'story',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Notification schedule @ "%s" was %s.', $this->time, $event);
    }

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
}
