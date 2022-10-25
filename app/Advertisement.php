<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Advertisement extends Model
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
        'location', 'network', 'type', 'unit', 'image', 'link', 'interval',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Advertisement #%d was %s.', $this->id, $event);
    }
}
