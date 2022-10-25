<?php

namespace App\Policies;

use App\Clip;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClipPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Clip $clip)
    {
        return $clip->user->enabled && (!$clip->private || ($user && $clip->user->id === $user->id));
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, Clip $clip)
    {
        return $clip->user->id === $user->id;
    }

    public function update(User $user, Clip $clip)
    {
        return $clip->user->id === $user->id;
    }

    public function comment(User $user, Clip $clip)
    {
        return $this->view($user, $clip) && $clip->comments;
    }
}
