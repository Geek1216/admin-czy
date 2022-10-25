<?php

namespace App\Http\Resources;

use App\Clip as ClipModel;
use App\Comment as CommentModel;
use App\User as UserModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class Notification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = ($this->data['user'] ?? null) ? UserModel::find($this->data['user']) : null;
        $clip = ($this->data['clip'] ?? null) ? ClipModel::find($this->data['clip']) : null;
        $comment = ($this->data['comment'] ?? null) ? CommentModel::find($this->data['comment']) : null;
        $class = explode('\\', $this->type);
        return [
            'id' => $this->id,
            'type' => Str::snake(end($class)),
            'read_at' => $this->read_at ? $this->read_at->toIso8601String() : null,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => $user ? User::make($user) : null,
            'clip' => $clip ? Clip::make($clip) : null,
            'comment' => $comment ? Comment::make($comment) : null,
        ];
    }
}
