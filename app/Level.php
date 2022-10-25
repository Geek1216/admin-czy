<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Level extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'name', 'color', 'order', 'followers', 'uploads', 'views', 'likes', 'reward',
    ];

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('Level "%s" was %s.', $this->name, $event);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_levels')
            ->using(UserLevel::class)
            ->withTimestamps();
    }
}
