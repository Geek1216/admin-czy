<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Thread extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        $participant = $this->users()->whereKeyNot($user->id)->first();
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => User::make($participant),
            'unread' => $this->isUnread($user->id),
            'latest' => Message::make($this->latest_message),
        ];
    }
}
