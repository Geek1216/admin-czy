<?php

namespace App\Http\Resources;

use App\Http\Resources\Sticker as StickerResource;
use App\Http\Resources\User as UserResource;
use App\Sticker;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'text' => $this->comment,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => UserResource::make($this->commentator)
        ];
        $data['hashtags'] = $this->tagsWithType('hashtags')->pluck('name');
        $mentions = $this->tagsWithType('mentions')->pluck('name');
        $mentions = User::query()->whereIn('id', $mentions)->get();
        $data['mentions'] = UserResource::collection($mentions);
        if ($this->comment && $this->comment[0] === '{') {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $json = json_decode($this->comment, true);
            if (isset($json['sticker'])) {
                $sticker = Sticker::query()->find($json['sticker']);
                if ($sticker) {
                    $data['sticker'] = StickerResource::make($sticker);
                }
            }
        }

        return $data;
    }
}
