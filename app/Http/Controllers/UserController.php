<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->whereNull('role')
            ->where('enabled', true);
        $q = $request->get('q');
        if ($q && ($q = ltrim($q, '@'))) {
            $query->where(function (Builder $query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('username', 'like', "%$q%");
            });
        } else {
            $query->where('verified', true)->latest();
        }

        $users = $query->withCount('followers')->latest()->paginate();
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        abort_if(!$user->enabled, 404);
        return UserResource::make($user);
    }

    public function find(string $username)
    {
        $user = User::query()
            ->where('enabled', true)
            ->where('username', $username)
            ->firstOrFail();
        return UserResource::make($user);
    }
}
