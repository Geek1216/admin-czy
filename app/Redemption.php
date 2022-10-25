<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Redemption extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'amount', 'mode', 'address', 'status', 'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Redemption #%d was %s.', $this->id, $event);
    }
}
