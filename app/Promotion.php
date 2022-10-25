<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Promotion extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sticky' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'image', 'sticky',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Promotion "%s" was %s.', Str::lower($this->title_short), $event);
    }

    public function getTitleShortAttribute()
    {
        if (mb_strlen($this->title) > 20) {
            return mb_substr($this->title, 0, 20) . '…';
        }
        return $this->title;
    }
}
