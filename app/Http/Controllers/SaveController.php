<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Story;
use App\User;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function store(Request $request, Clip $clip, Story $story)
    {
        $this->authorize('view', $clip, $story);
        /** @var User $user */
        $user = $request->user();
        if (!$clip->isFavoritedBy($user)) {
            $user->favorite($clip);
        }
        if (!$story->isFavoritedBy($user)) {
            $user->favorite($story);
        }
    }

    public function destroy(Request $request, Clip $clip, Story $story)
    {
        $this->authorize('view', $clip);
        /** @var User $user */
        $user = $request->user();
        if ($clip->isFavoritedBy($user)) {
            $user->unfavorite($clip);
        }
        if ($story->isFavoritedBy($user)) {
            $user->unfavorite($story);
        }
    }
}
