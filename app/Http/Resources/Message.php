<?php

namespace App\Http\Resources;

use App\Http\Resources\Sticker as StickerResource;
use App\Sticker;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => User::make($this->user),
        ];
        if ($this->body && $this->body[0] === '{') {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $json = json_decode($this->body, true);
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
