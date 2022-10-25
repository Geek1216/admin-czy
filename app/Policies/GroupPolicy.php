<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    // public function view(?User $user, Clip $clip)
    // {
    //     return $clip->user->enabled && (!$clip->private || ($user && $clip->user->id === $user->id));
    // }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, Group $group)
    {
        return $group->user->id === $user->id;
    }

    public function update(User $user, Group $group)
    {
        return $group->user->id === $user->id;
    }
}
