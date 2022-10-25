<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Jobs\SendNotification;
use App\Notifications\LikedYourClip;
use App\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Clip $clip)
    {
        $this->authorize('view', $clip);
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
    }

    public function destroy(Request $request, Clip $clip)
    {
        $this->authorize('view', $clip);
        /** @var User $user */
        $user = $request->user();
        if ($clip->isLikedBy($user)) {
            $user->unlike($clip);
        }
    }
}
