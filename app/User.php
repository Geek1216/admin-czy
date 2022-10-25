<?php

namespace App;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Traits\HasGift;
use BeyondCode\Comments\Contracts\Commentator;
use Cmgmyr\Messenger\Traits\Messagable;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Overtrue\LaravelFollow\Followable;
use Overtrue\LaravelLike\Like;
use Overtrue\LaravelLike\Traits\Liker;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements Commentator
{
    use BlockUnblock, Followable, HasApiTokens, Liker, Favoriter, LogsActivity, Notifiable, Messagable;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $casts = [
        'enabled' => 'boolean',
        'verified' => 'boolean',
        'links' => 'array',
    ];

    protected $dates = [
        'email_verified_at',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'enabled', 'username', 'bio', 'verified', 'photo', 'phone', 'facebook_id',
        'google_id', 'links', 'location', 'latitude', 'longitude',
    ];

    protected $hidden = [
        'password', 'remember_token', 'facebook_id', 'google_id',
    ];

    public function clips()
    {
        return $this->hasMany(Clip::class);
    }

    //Added by Nikita Ahuja On 25th March, 2022
    public function stories()
    {
        return $this->hasMany(Story::class);
    }
    //Added by Nikita Ahuja On 25th March, 2022

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return sprintf('User "%s" was %s.', Str::lower($this->name), $event);
    }

    public function getLikesTotalAttribute()
    {
        return Cache::remember("user_{$this->id}_likes", now()->addHours(1), function () {
            return Like::query()
                ->whereHasMorph('likeable', Clip::class, function (Builder $query) {
                    $query->whereHas('user', function (Builder $query) {
                        $query->whereKey($this->id);
                    });
                })
                ->whereHasMorph('likeable', Story::class, function (Builder $query) {
                    $query->whereHas('user', function (Builder $query) {
                        $query->whereKey($this->id);
                    });
                })
                ->count();
        });
    }

    public function getViewsTotalAttribute()
    {
        return Cache::remember("user_{$this->id}_views", now()->addHours(1), function () {
            return View::query()
                ->whereHasMorph('viewable', Clip::class, function (Builder $query) {
                    $query->whereHas('user', function (Builder $query) {
                        $query->whereKey($this->id);
                    });
                })
                ->whereHasMorph('viewable', Story::class, function (Builder $query) {
                    $query->whereHas('user', function (Builder $query) {
                        $query->whereKey($this->id);
                    });
                })
                ->count();
        });
    }

    public function needsCommentApproval($model): bool
    {
        return false;
    }

    public function reportedReport()
    {
        return $this->hasMany(Report::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'subject');
    }

    public function suggestion()
    {
        return $this->hasOne(Suggestion::class);
    }

    public function tapActivity(Activity $activity, string $event)
    {
        if ($properties = $activity->changes()) {
            foreach ($this->hidden as $attr) {
                if (Arr::has($properties, "attributes.$attr")) {
                    $attributes = $properties->get('attributes');
                    $attributes[$attr] = '*hidden*';
                    $properties->put('attributes', $attributes);
                }
                if (Arr::has($properties, "old.$attr")) {
                    $old = $properties->get('old');
                    $old[$attr] = '*hidden*';
                    $properties->put('old', $old);
                }
            }
            $activity->properties = $properties;
        }
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
}
