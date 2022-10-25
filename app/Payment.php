<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'reference', 'amount', 'data', 'status',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Payment "%s" was %s.', Str::lower($this->reference_short), $event);
    }

    public function getReferenceShortAttribute()
    {
        return $this->reference ? substr($this->reference, 24, 12) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
