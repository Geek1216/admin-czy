<?php

namespace App;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LiveStream extends Model implements Viewable
{
    use InteractsWithViews;

    protected $casts = [
        'private' => 'boolean',
        'data' => 'array',
    ];

    protected $dates = [
        'ends_at',
    ];

    protected $fillable = [
        'service', 'private', 'ends_at', 'data', 'status',
    ];

    public function getViewsTotalAttribute()
    {
        return Cache::remember("live_stream_{$this->id}_views", now()->addMinute(), function () {
            return views($this)->count();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
