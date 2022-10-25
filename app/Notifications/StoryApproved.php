<?php

namespace App\Notifications;

use App\Story;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StoryApproved extends Notification
{
    use Queueable;

    private $story;

    public function __construct(Story $story)
    {
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
        ];
    }
}
