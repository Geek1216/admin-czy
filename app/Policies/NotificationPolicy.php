<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, DatabaseNotification $notification)
    {
        return $notification->notifiable instanceof User && $notification->notifiable->id === $user->id;
    }

    public function delete(User $user, DatabaseNotification $notification)
    {
        return $this->view($user, $notification);
    }
}
