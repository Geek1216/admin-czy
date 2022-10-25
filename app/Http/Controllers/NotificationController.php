<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification as NotificationResource;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $results = $user->notifications()->latest()->paginate(15);
        return NotificationResource::collection($results);
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
