<?php

namespace App\Notifications;

use App\Clip;
use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentedOnYourClip extends Notification
{
    use Queueable;

    private $clip;

    private $comment;

    private $user;

    public function __construct(User $user, Clip $clip, Comment $comment)
    {
        $this->user = $user;
        $this->clip = $clip;
        $this->comment = $comment;
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
            'comment' => $this->comment->id,
            'user' => $this->user->id,
        ];
    }
}
