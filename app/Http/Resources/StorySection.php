<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class StorySection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $stories_count = $this->stories()
            ->where('approved', true)
            ->where('private', false)
            ->whereHas('user', function (Builder $query) {
                return $query->where('enabled', true);
            })
            ->count();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'stories_count' => $stories_count,
        ];
    }
}
