<?php

namespace App\Http\Resources;

use App\User as UserModel;
use App\Group as GroupModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use DB;
class Group extends JsonResource
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
        $category_name = DB::table('categories')->select('category_name')->where('id', $this->category)->first();

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'unique_name' => !empty($this->unique_name) ? $this->unique_name : '',
            'category' => $this->category,
            'category_name' => $category_name->category_name,
            'thumbnail' => $cdn ? $cdn . $this->thumbnail : Storage::cloud()->url($this->thumbnail),
            'link' => $this->link,
            'user' => User::make($this->user),
        ];
        return $data;
    }
}
