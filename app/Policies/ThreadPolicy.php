<?php

namespace App\Policies;

use App\User;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Thread $thread)
    {
        return $thread->hasParticipant($user->id);
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Thread $thread)
    {
        if ($this->view($user, $thread)) {
            $participants = $thread->users()->whereKeyNot($user->id)->get();
            foreach ($participants as $participant) {
                if ($participant->isBlocking($user)) {
                    return false;
                } else if ($user->isBlocking($participant)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
