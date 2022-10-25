<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Song extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $clips_count = $this->clips()
            ->where('approved', true)
            ->where('private', false)
            ->whereHas('user', function (Builder $query) {
                return $query->where('enabled', true);
            })
            ->count();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'audio' => Storage::cloud()->url($this->audio),
            'cover' => $this->cover ? Storage::cloud()->url($this->cover) : null,
            'duration' => $this->duration,
            'details' => $this->details,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'sections' => SongSection::collection($this->sections),
            'clips_count' => $clips_count,
        ];
    }
}
