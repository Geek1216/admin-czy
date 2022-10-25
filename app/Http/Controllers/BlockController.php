<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = $user->blocked()->withCount(['stories', 'clips', 'followers', 'followings'])->latest();
        return UserResource::collection($query->paginate());
    }

    public function store(Request $request, User $user)
    {
        /** @var User $me */
        $me = $request->user();
        if (!$me->isBlocking($user)) {
            $me->block($user);
        }
    }

    public function destroy(Request $request, User $user)
    {
        /** @var User $me */
        $me = $request->user();
        if ($me->isBlocking($user)) {
            $me->unblock($user);
        }
    }
}
