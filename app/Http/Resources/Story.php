<?php

namespace App\Http\Resources;

use App\User as UserModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Story extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        static $cdn = false;
        if ($cdn === false) {
            $cdn = config('fixtures.cdn_url');
        }
        /** @var \App\User|null $user */
        $user = $request->user();
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'media_type' => $this->media_type,
            'media' => $cdn ? $cdn . $this->media : Storage::cloud()->url($this->media),
            'screenshot' => $cdn ? $cdn . $this->screenshot : Storage::cloud()->url($this->screenshot),
            'preview' => $cdn ? $cdn . $this->preview : Storage::cloud()->url($this->preview),
            'description' => $this->description,
            'language' => $this->language,
            'private' => $this->private,
            'comments' => $this->comments,
            'duration' => $this->duration,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => User::make($this->user),
            'song' => Song::make($this->song),
            'sections' => StorySection::collection($this->sections),
            'views_count' => views($this->resource)->count(),
            'likes_count' => $this->likes_count ?: $this->likes()->count(),
            'comments_count' => $this->comments_count ?: $this->comments()->count(),
            'liked' => $user && $user->hasLiked($this->resource),
            'saved' => $user && $user->hasFavorited($this->resource),
        ];
        $data['hashtags'] = $this->tagsWithType('hashtags')->pluck('name');
        $mentions = $this->tagsWithType('mentions')->pluck('name');
        $mentions = UserModel::whereIn('id', $mentions)->get();
        $data['mentions'] = User::collection($mentions);
        // $user_id = array_column($data,'id');
        return $data;
    }
}
