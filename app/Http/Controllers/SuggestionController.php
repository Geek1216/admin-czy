<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;

class SuggestionController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->rightJoin('suggestions',  'users.id', '=', 'suggestions.user_id')
            ->orderBy('suggestions.order')
            ->withCount('followers')
            ->paginate();
        return UserResource::collection($users);
    }
}
