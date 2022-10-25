<?php

namespace App\Http\Controllers;

use App\Clip;
use App\User;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function store(Request $request, Clip $clip)
    {
        $this->authorize('view', $clip);
        /** @var User $user */
        $user = $request->user();
        if (!$clip->isFavoritedBy($user)) {
            $user->favorite($clip);
        }
    }

    public function destroy(Request $request, Clip $clip)
    {
        $this->authorize('view', $clip);
        /** @var User $user */
        $user = $request->user();
        if ($clip->isFavoritedBy($user)) {
            $user->unfavorite($clip);
        }
    }
}
