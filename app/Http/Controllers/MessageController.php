<?php

namespace App\Http\Controllers;

use App\Http\Resources\Message as MessageResource;
use App\Jobs\SendNotification;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request, Thread $thread)
    {
        $this->authorize('view', $thread);
        $query = $thread->messages()->latest();
        $thread->markAsRead($request->user()->id);
        return MessageResource::collection($query->paginate());
    }

    public function store(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $data = $this->validate($request, [
            'body' => ['required', 'string', 'max:1024'],
        ]);
        $user = $request->user();
        /** @var Message $message */
        $message = $thread->messages()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);
        dispatch(new SendNotification(
            __('notifications.sent_you_message.title', ['user' => $user->username]),
            $data['body'],
            ['thread' => $thread->id],
            $message->recipients()->first()->user
        ));
        return MessageResource::make($message);
    }

    public function destroy(Request $request, Thread $thread, Message $message)
    {
        $this->authorize('view', $thread);
        abort_if($message->user->id !== $request->user()->id, 403);
        $message->delete();
    }
}
