<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\User|null $user */
        $user = $request->user();
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo ? Storage::cloud()->url($this->photo) : null,
            'username' => $this->username,
            'bio' => $this->bio,
            'verified' => $this->verified,
            'links' => $this->links,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'followers_count' => $this->followers_count ?: $this->followers()->count(),
            'followed_count' => $this->followings_count ?: $this->followings()->count(),
            'clips_count' => $this->clips_count ?: $this->clips()->count(),
            'likes_count' => $this->likes_total,
            'views_count' => $this->views_total,
            'me' => $user && $this->id === $user->id,
            'follower' => $user && $user->isFollowedBy($this->resource),
            'followed' => $user && $user->isFollowing($this->resource),
            'blocked' => $user && $user->isBlockedBy($this->resource),
            'blocking' => $user && $user->isBlocking($this->resource),
        ];
        if ($data['me']) {
            $data += [
                'email' => $this->email,
                'phone' => $this->phone,
            ];
        }
        return $data;
    }
}
