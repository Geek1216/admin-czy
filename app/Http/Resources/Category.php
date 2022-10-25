<?php

namespace App\Http\Resources;

use App\User as UserModel;
use App\Category as CategoryModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use DB;
class Category extends JsonResource
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
        $groups = DB::table('groups')->select('id', 'user_id', 'name', 'unique_name', 'thumbnail', 'link')->where('category', $this->id)->get();
        $groupArr = [];
        foreach($groups as $group){
            $groupArr[] = [
                'id' => $group->id,
                'user_id' => $group->user_id,
                'username' => DB::table('users')->select('username')->where('id', $group->user_id)->first()->username,
                'name' => $group->name,
                'unique_name' => !empty($group->unique_name) ? $group->unique_name : '',
                'thumbnail' => $cdn ? $cdn . $group->thumbnail : Storage::cloud()->url($group->thumbnail),
                'link' => $group->link
            ];
        }
        $data = [
            'id' => $this->id,
            'name' => $this->category_name,
            'group' => $groupArr
        ];
        return $data;
    }
}
