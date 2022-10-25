<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Jobs\FindMentionsHashtags;
use App\Jobs\SendNotification;
use App\Notifications\CommentedOnYourClip;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(Clip $clip)
    {
        $query = $clip->comments();
        $comments = $query->latest()->with(['commentator'])->paginate();
        return CommentResource::collection($comments);
    }

    public function store(Request $request, Clip $clip)
    {
        $this->authorize('comment', $clip);
        $data = $this->validate($request, [
            'text' => ['required', 'string', 'max:1024'],
        ]);
        $comment = $clip->comment($data['text']);
        if ($clip->user->id !== $comment->commentator->id) {
            $clip->user->notify(new CommentedOnYourClip($comment->commentator, $clip, $comment));
            dispatch(new SendNotification(
                __('notifications.commented_on_your_clip.title', ['user' => $comment->commentator->username]),
                __('notifications.commented_on_your_clip.body'),
                ['clip' => $clip->id],
                $clip->user,
                $clip
            ));
        }

        dispatch(new FindMentionsHashtags($comment, $comment->comment));
        return CommentResource::make($comment);
    }

    public function destroy(Clip $clip, Comment $comment)
    {
        $comment->delete();
    }
}
