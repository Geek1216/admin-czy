<?php

namespace App\Policies;

use App\LiveStream;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LiveStreamPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, LiveStream $liveStream)
    {
        if ($liveStream->status !== 'streaming') {
            return false;
        }
        if (!$liveStream->private) {
            return true;
        }
        if ($user) {
            $owned = $user->getKey() === $liveStream->user->getKey();
            return $owned || $user->isFollowing($liveStream->user);
        }
        return false;
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, LiveStream $liveStream)
    {
        return $liveStream->status !== 'ended' && $user->getKey() === $liveStream->user->getKey();
    }
}
