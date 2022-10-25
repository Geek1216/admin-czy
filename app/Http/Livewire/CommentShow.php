<?php

namespace App\Http\Livewire;

use App\Comment;
use Livewire\Component;

class CommentShow extends Component
{
    public $comment;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        $activities = $this->comment->activities()->latest()->paginate();
        return view('livewire.comment-show', compact('activities'));
    }
}
