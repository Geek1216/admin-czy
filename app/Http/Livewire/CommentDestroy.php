<?php

namespace App\Http\Livewire;

use App\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CommentDestroy extends Component
{
    use AuthorizesRequests;

    public $comment;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        return view('livewire.comment-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->comment->delete();
        flash()->info(__('Comment :comment has been deleted.', ['comment' => $this->comment->comment_short]));
        $this->redirect(route('comments.index'));
    }
}
