<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StickerSection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'stickers_count' => $this->stickers_count ?: $this->stickers()->count(),
        ];
    }
}
