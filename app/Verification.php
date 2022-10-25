<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Verification extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document', 'status',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Verification "%s" was %s.', Str::lower($this->user->name), $event);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
