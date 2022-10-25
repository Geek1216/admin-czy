<?php

namespace App\Notifications;

use App\Story;
use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentedOnYourStory extends Notification
{
    use Queueable;

    private $story;

    private $comment;

    private $user;

    public function __construct(User $user, Story $story, Comment $comment)
    {
        $this->user = $user;
        $this->story = $story;
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
            'story' => $this->story->id,
            'comment' => $this->comment->id,
            'user' => $this->user->id,
        ];
    }
}
