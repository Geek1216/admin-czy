<?php

namespace App\Notifications;

use App\Clip;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LikedYourClip extends Notification
{
    use Queueable;

    private $clip;

    private $user;

    public function __construct(User $user, Clip $clip)
    {
        $this->user = $user;
        $this->clip = $clip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'clip' => $this->clip->id,
            'user' => $this->user->id,
        ];
    }
}
