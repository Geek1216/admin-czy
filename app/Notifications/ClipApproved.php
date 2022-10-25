<?php

namespace App\Notifications;

use App\Clip;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClipApproved extends Notification
{
    use Queueable;

    private $clip;

    public function __construct(Clip $clip)
    {
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
        ];
    }
}
