<?php

/*
 * This file is part of the overtrue/laravel-follow
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Overtrue\LaravelFollow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Trait Followable.
 *
 * @property \Illuminate\Database\Eloquent\Collection $followings
 * @property \Illuminate\Database\Eloquent\Collection $followers
 */
trait Followable
{
    /**
     * @return bool
     */
    public function needsToApproveFollowRequests()
    {
        return false;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     *
     * @return array
     */
    public function follow($user)
    {
        $isPending = $user->needsToApproveFollowRequests() ?: false;

        $this->followings()->attach($user, [
            'accepted_at' => $isPending ? null : now()
        ]);

        return ['pending' => $isPending];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     */
    public function unfollow($user)
    {
        $this->followings()->detach($user);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     *
     */
    public function toggleFollow($user)
    {
        $this->isFollowing($user) ? $this->unfollow($user) : $this->follow($user);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     */
    public function rejectFollowRequestFrom($user)
    {
        $this->followers()->detach($user);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     */
    public function acceptFollowRequestFrom($user)
    {
        $this->followers()->updateExistingPivot($user, ['accepted_at' => now()]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     */
    public function hasRequestedToFollow($user): bool
    {
        if ($user instanceof Model) {
            $user = $user->getKey();
        }

        /* @var \Illuminate\Database\Eloquent\Model $this */
        if ($this->relationLoaded('followings')) {
            return $this->followings
                ->where('pivot.accepted_at', '===', null)
                ->contains($user);
        }

        return $this->followings()
            ->wherePivot('accepted_at', null)
            ->where($this->getQualifiedKeyName(), $user)
            ->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     *
     * @return bool
     */
    public function isFollowing($user)
    {
        if ($user instanceof Model) {
            $user = $user->getKey();
        }

        /* @var \Illuminate\Database\Eloquent\Model $this */
        if ($this->relationLoaded('followings')) {
            return $this->followings
                ->where('pivot.accepted_at', '!==', null)
                ->contains($user);
        }

        return $this->followings()
            ->wherePivot('accepted_at', '!=', null)
            ->where($this->getQualifiedKeyName(), $user)
            ->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     *
     * @return bool
     */
    public function isFollowedBy($user)
    {
        if ($user instanceof Model) {
            $user = $user->getKey();
        }

        /* @var \Illuminate\Database\Eloquent\Model $this */
        if ($this->relationLoaded('followers')) {
            return $this->followers
                ->where('pivot.accepted_at', '!==', null)
                ->contains($user);
        }

        return $this->followers()
            ->wherePivot('accepted_at', '!=', null)
            ->where($this->getQualifiedKeyName(), $user)
            ->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|int $user
     *
     * @return bool
     */
    public function areFollowingEachOther($user)
    {
        /* @var \Illuminate\Database\Eloquent\Model $user*/
        return $this->isFollowing($user) && $this->isFollowedBy($user);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        /* @var \Illuminate\Database\Eloquent\Model $this */
        return $this->belongsToMany(
            __CLASS__,
            \config('follow.relation_table', 'user_follower'),
            'following_id',
            'follower_id'
        )->withPivot('accepted_at')->withTimestamps()->using(UserFollower::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        /* @var \Illuminate\Database\Eloquent\Model $this */
        return $this->belongsToMany(
            __CLASS__,
            \config('follow.relation_table', 'user_follower'),
            'follower_id',
            'following_id'
        )->withPivot('accepted_at')->withTimestamps()->using(UserFollower::class);
    }

    public function attachFollowStatus($followables, callable $resolver = null)
    {
        $returnFirst = false;

        switch (true) {
            case $followables instanceof Model:
                $returnFirst = true;
                $followables = \collect([$followables]);
                break;
            case $followables instanceof LengthAwarePaginator:
                $followables = $followables->getCollection();
                break;
            case $followables instanceof Paginator:
                $followables = \collect($followables->items());
                break;
            case \is_array($followables):
                $followables = \collect($followables);
                break;
        }

        \abort_if(!($followables instanceof Collection), 422, 'Invalid $followables type.');

        $followed = $this->followings()->wherePivot('accepted_at', '!=', null)->pluck('following_id');

        $followables->map(function ($followable) use ($followed, $resolver) {
            $resolver = $resolver ?? function ($m) { return $m; };
            $followable = $resolver($followable);

            if ($followable && \in_array(Followable::class, \class_uses($followable))) {
                $followable->setAttribute('has_followed', $followed->contains($followable->getKey()));
            }
        });

        return $returnFirst ? $followables->first() : $followables;
    }
}
