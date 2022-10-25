<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Advertisement extends JsonResource
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
            'location' => $this->location,
            'network' => $this->network,
            'type' => $this->type,
            'interval' => $this->interval,
        ];
        if ($this->network === 'custom') {
            $data['image'] = Storage::cloud()->url($this->image);
            $data['link'] = $this->link;
        } else {
            $data['unit'] = $this->unit;
        }
        return $data;
    }
}
