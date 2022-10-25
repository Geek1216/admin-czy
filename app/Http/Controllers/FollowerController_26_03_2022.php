<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Jobs\SendNotification;
use App\Notifications\StartedFollowingYou;
use App\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function index(Request $request, User $user)
    {
        abort_if(!$user->enabled, 404);
        $following = $request->get('following') === 'true';
        $query = $following ? $user->followings() : $user->followers();
        $users = $query->withCount(['clips', 'followers', 'followings'])->latest()->paginate();
        return UserResource::collection($users);
    }

    public function store(Request $request, User $user)
    {
        /** @var User $self */
        $self = $request->user();
        if ($self->id !== $user->id && !$self->isFollowing($user)) {
            $self->follow($user);
            $user->notify(new StartedFollowingYou($self));
            dispatch(new SendNotification(
                __('notifications.started_following_you.title', ['user' => $self->username]),
                __('notifications.started_following_you.body'),
                ['user' => $self->id],
                $user
            ));
        }
    }

    public function destroy(Request $request, User $user)
    {
        /** @var User $self */
        $self = $request->user();
        if ($self->isFollowing($user)) {
            $self->unfollow($user);
        }
    }
}
