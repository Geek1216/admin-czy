<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'reason', 'message', 'status',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Report #%d was %s.', $this->id, $event);
    }

    public function getMessageShortAttribute()
    {
        if (mb_strlen($this->message) > 20) {
            return mb_substr($this->message, 0, 20) . '…';
        }
        return $this->message;
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
