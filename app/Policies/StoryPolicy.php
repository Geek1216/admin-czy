<?php

namespace App\Policies;

use App\Story;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Story $story)
    {
        return $story->user->enabled && (!$story->private || ($user && $story->user->id === $user->id));
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, Story $story)
    {
        return $story->user->id === $user->id;
    }

    public function update(User $user, Story $story)
    {
        return $story->user->id === $user->id;
    }

    public function comment(User $user, Story $story)
    {
        return $this->view($user, $story) && $story->comments;
    }
}
