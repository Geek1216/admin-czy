<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Comment $comment)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function delete(User $user, Comment $comment)
    {
        return $comment->commentator->id === $user->id;
    }
}
