<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Story;
use App\Jobs\SendNotification;
use App\Notifications\LikedYourClip;
use App\Notifications\LikedYourStory;
use App\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Clip $clip, Story $story)
    {
        $this->authorize('view', $clip, $story);
        /** @var User $user */
        $user = $request->user();
        if ($clip->user->id !== $user->id && !$clip->isLikedBy($user)) {
            $user->like($clip);
            $clip->user->notify(new LikedYourClip($user, $clip));
            dispatch(new SendNotification(
                __('notifications.liked_your_clip.title', ['user' => $user->username]),
                __('notifications.liked_your_clip.body'),
                ['clip' => $clip->id],
                $clip->user
            ));
        }
        if(!empty($story) && !empty($story->user->id)){
            if ($story->user->id !== $user->id && !$story->isLikedBy($user)) {
                $user->like($story);
                $story->user->notify(new LikedYourStory($user, $story));
                dispatch(new SendNotification(
                    __('notifications.liked_your_story.title', ['user' => $user->username]),
                    __('notifications.liked_your_story.body'),
                    ['story' => $story->id],
                    $story->user
                ));
            }
        }
    }

    public function destroy(Request $request, Clip $clip, Story $story)
    {
        $this->authorize('view', $clip, $story);
        /** @var User $user */
        $user = $request->user();
        if ($clip->isLikedBy($user)) {
            $user->unlike($clip);
        }
        if ($story->isLikedBy($user)) {
            $user->unlike($story);
        }
    }
}
