<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Suggestion extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'order',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Suggestion for "%s" was %s.', Str::lower($this->user->name), $event);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
