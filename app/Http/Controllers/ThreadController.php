<?php

namespace App\Http\Controllers;

use App\Http\Resources\Thread as ThreadResource;
use App\User;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Thread::class);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Thread::query()
            ->whereHas('users', function (Builder $query) use ($user) {
                $query->whereKey($user->id);
            })
            ->whereHas('messages')
            ->latest('updated_at');
        return ThreadResource::collection($query->paginate());
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'user' => ['required', 'integer', 'exists:users,id'],
        ]);
        /** @var User $user */
        $user = $request->user();
        /** @var User $other */
        $other = User::query()
            ->where('enabled', true)
            ->findOrFail($data['user']);
        abort_if($user->isBlocking($other) || $user->isBlockedBy($other), 403);
        /** @var Thread|null $thread */
        $thread = Thread::query()
            ->whereHas('users', function (Builder $query) use ($user) {
                $query->whereKey($user->id);
            })
            ->whereHas('users', function (Builder $query) use ($other) {
                $query->whereKey($other->id);
            })
            ->first();
        if (empty($thread)) {
            $thread = Thread::create(['subject' => Str::uuid()->toString()]);
            $thread->addParticipant([
                $user->id,
                $other->id,
            ]);
        }
        return ThreadResource::make($thread);
    }

    public function show(Thread $thread)
    {
        $resource = ThreadResource::make($thread);
        $resource->detailed = true;
        return $resource;
    }
}
