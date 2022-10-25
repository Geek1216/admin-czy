<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Followable.
 *
 * @property \Illuminate\Database\Eloquent\Collection $blocked
 * @property \Illuminate\Database\Eloquent\Collection $blockers
 */
trait BlockUnblock
{
    public function block($user)
    {
        $this->blocked()->attach($user);
    }

    public function blocked()
    {
        return $this->belongsToMany(
            __CLASS__,
            'blocked_users',
            'blocker_id',
            'blocked_id'
        )->withTimestamps();
    }

    public function blockers()
    {
        return $this->belongsToMany(
            __CLASS__,
            'blocked_users',
            'blocked_id',
            'blocker_id'
        )->withTimestamps();
    }

    public function isBlockedBy($user)
    {
        if ($user instanceof Model) {
            $user = $user->getKey();
        }

        if ($this->relationLoaded('blockers')) {
            return $this->blockers->contains($user);
        }

        return $this->blockers()
            ->where($this->getQualifiedKeyName(), $user)
            ->exists();
    }

    public function isBlocking($user)
    {
        if ($user instanceof Model) {
            $user = $user->getKey();
        }

        if ($this->relationLoaded('blocking')) {
            return $this->blocked->contains($user);
        }

        return $this->blocked()
            ->where($this->getQualifiedKeyName(), $user)
            ->exists();
    }

    public function toggleBlock($user)
    {
        $this->isBlocking($user) ? $this->unblock($user) : $this->block($user);
    }

    public function unblock($user)
    {
        $this->blocked()->detach($user);
    }
}
