<?php

namespace App\Notifications;

use App\Story;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LikedYourStory extends Notification
{
    use Queueable;

    private $story;

    private $user;

    public function __construct(User $user, Story $story)
    {
        $this->user = $user;
        $this->story = $story;
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
            'story' => $this->story->id,
            'user' => $this->user->id,
        ];
    }
}
