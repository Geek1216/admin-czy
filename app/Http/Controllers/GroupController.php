<?php

namespace App\Http\Controllers;

use App\Group;
use App\Category;
use App\Http\Resources\Group as GroupResource;
use App\Http\Resources\Category as CategoryResource;
use App\Jobs\SendNotification;
use App\User;
use URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Group::class);
    }

    public function index(Request $request)
    {
        /** @var Builder $query */
        $query = Group::query();
        $group = $query->with(['user'])
                ->orderByDesc('id')
                ->paginate($request->get('count', 15));

        return GroupResource::collection($group);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'thumbnail' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/jpg',
                'max:' . config('fixtures.upload_limits.group.thumbnail'),
            ],
            'name' => ['string'],
            'category' => ['integer','exists:categories,id'],
            'user_id' => ['integer', 'exists:users,id'],
            'link' => ['string','nullable'],
        ]);
        /** @var UploadedFile $screenshot */
        if(!empty($data['thumbnail'])){
            $thumbnail = $data['thumbnail'];
            $name = Str::random(15) . '.' . $thumbnail->guessExtension();
            $data['thumbnail'] = $thumbnail->storePubliclyAs('thumbnail', $name, config('filesystems.cloud'));
        }
        $data['name'] = $data['name'] ?? false;
        $data['unique_name'] = strtolower(str_replace(' ', '_', $data['name']).'_'.date('Y_m_d_H_i_s'));
        $data['category'] = $data['category'] ?? false;
        $data['user_id'] = $data['user_id'] ?? false;
        $data['link'] = $data['link'] ?? '';
        /** @var User $user */
        $user = $request->user();
        /** @var Clip $clip */
        $group = $user->groups()->make($data);
        $group->save();
        // SendNotification::dispatch(
        //     __('notifications.posted_new_clip.title', ['user' => $user->username]),
        //     __('notifications.posted_new_clip.body'),
        //     null,
        //     $user,
        //     $clip,
        //     null,
        //     true
        // );
        return GroupResource::make($group);
    }

    public function show(Group $group)
    {
        $resource = GroupResource::make($group);
        views($group)->record();
        return $resource;
    }

    public function update(Group $group, Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['string'],
            'category' => ['integer'],
        ]);
        $group->fill($data);
        $group->save();

        return GroupResource::make($group);
    }

    public function groupUpdate(Group $group, Request $request)
    {
        
        $url = URL::current();
        $id = explode('/', $url);
        if(!empty($request->username)){
            $get_user_id = DB::select('select * from users where username LIKE "'.$request->username.'" order by id desc limit 1');
            if(!empty($id)){
                $get_group = DB::select('select * from groups where id = '.$id[5]);
                if(!empty($get_group)){
                    DB::update('update groups set user_id = '.$get_user_id[0]->id .' where id = '.$id[5]);
                }
            }
        }
        $group = Group::findOrFail($id[5]);
        return GroupResource::make($group);
    }
    public function destroy(Group $group)
    {
        $group->delete();
    }

    public function groupListCategoryWise(Request $request)
    {
        if(!empty($request->category_id)){
            $query = Category::where('id',$request->category_id);
            $category = $query
                    ->orderByDesc('id')
                    ->get();

            return CategoryResource::collection($category);
        }
    }

    public function groupListUserWise(Request $request)
    {
        static $cdn = false;
        if ($cdn === false) {
            $cdn = config('fixtures.cdn_url');
        }
        if(!empty($request->user_id)){
            $user = $request->user();
            $groups = DB::table('groups')->select('id', 'user_id', 'name', 'unique_name', 'thumbnail', 'link')->where('user_id',$request->user_id)->get();
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
        }
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'group' => $groupArr
        ];
        return $data;
    }
}
