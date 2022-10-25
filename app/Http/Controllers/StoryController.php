<?php

namespace App\Http\Controllers;

use App\Story;
use App\Http\Resources\Story as StoryResource;
use App\Jobs\FindMentionsHashtags;
use App\Jobs\SendNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class StoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Story::class);
    }

    public function index1(Request $request)
    {
        /** @var Builder $query */
        $query = Story::query()
            ->where('approved', true)
            // ->where('created_at' >= now() - interval 24 hour
            ->where('stories.created_at', '>=', Carbon::now()->subHours(24)->toDateTimeString())
            ->whereHas('user', function (Builder $query) {
                return $query->where('enabled', true);
            });
        /** @var User|null $user */
        $user = $request->user();
        if ($user) {
            $query->where(function (Builder $query) use ($user) {
                $query->where('private', false)
                    ->orWhereHas('user', function (Builder $query) use ($user) {
                        return $query->whereKey($user->id);
                    });
            });
        } else {
            $query->where('private', false);
        }

        $q = $request->get('q');
        if ($q) {
            $query->where('description', 'like', "%$q%");
        }

        $mine = $request->get('mine') === 'true';
        if ($mine && $user) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                return $query->whereKey($user->id)
                    ->where('enabled', true);
            });
        }

        $liked = $request->get('liked') === 'true';
        if ($liked && $user) {
            $query->whereHas('likers', function (Builder $query) use ($user) {
                return $query->whereKey($user->id);
            });
        }

        $saved = $request->get('saved') === 'true';
        if ($saved && $user) {
            $query->whereHas('favoriters', function (Builder $query) use ($user) {
                return $query->whereKey($user->id);
            });
        }

        $following = $request->get('following') === 'true';
        if ($following && $user) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                $query->whereHas('followers', function (Builder $query) use ($user) {
                    return $query->whereKey($user->id);
                });
            });
        }

        $user = $request->get('user');
        if ($user) {
            $query->whereHas('user', function (Builder $query) use ($user) {
                return $query->whereKey($user);
            });
        }

        $song = $request->get('song');
        if ($song) {
            $query->whereHas('song', function (Builder $query) use ($song) {
                return $query->whereKey($song);
            });
        }

        $sections = (array)$request->get('sections');
        if (count($sections)) {
            $query->whereHas('sections', function (Builder $query) use ($sections) {
                return $query->whereIn('id', $sections);
            });
        }

        $hashtags = (array)$request->get('hashtags');
        if (count($hashtags)) {
            $query->withAnyTags($hashtags, 'hashtags');
        }

        $languages = (array)$request->get('languages');
        $languages = count($languages) ? implode(',', $languages) : '';
        $seed = $request->get('seed');
        $first = $request->get('first');
        if ($seed) {
            if (is_numeric($seed)) {
                $seed = (int) $seed;
                if ($seed < 1000 || $seed > 99999) {
                    $seed = rand(1000, 99999);
                }
            } else {
                $seed = '';
            }

            $sub = (clone $query)
                ->select('stories.id', DB::raw('RAND(' . $seed . ') as seed'), 'users.verified')
                ->join('users', 'stories.user_id', '=', 'users.id')
                ->take(999);
            $query->joinSub(
                $sub,
                '_',
                'stories.id',
                '=',
                '_.id',
                null
            );
            $seen = $request->get('seen');
            if (is_numeric($seen)) {
                $seen = Carbon::createFromTimestampMs($seen);
            } else {
                $seen = now();
            }

            if ($first) {
                $query->selectRaw(
                    '*,
                    CASE
                    WHEN stories.id = ? THEN 3
                    WHEN created_at >= ? AND _.verified = 1 THEN 2
                    WHEN created_at >= ? THEN 1
                    ELSE 0
                    END AS weight1,
                    IF (LOCATE(stories.language, ?) > 0, 1, 0) AS weight2',
                    [$first, $seen, $seen, $languages]
                );
            } else {
                $query->selectRaw(
                    '*,
                    CASE
                    WHEN created_at >= ? AND _.verified = 1 THEN 2
                    WHEN created_at >= ? THEN 1
                    ELSE 0
                    END AS weight1,
                    IF (LOCATE(stories.language, ?) > 0, 1, 0) AS weight2',
                    [$seen, $seen, $languages]
                );
            }

            $stories = $query->with(['user', 'song'])
                ->withCount(['likes', 'comments'])
                ->orderByDesc('weight1')
                ->orderByDesc('weight2')
                ->orderByDesc('seed')
                ->paginate($request->get('count', 15));
        } else {
            $before = $request->get('before');
            $after = $request->get('after');
            $query = $query->selectRaw('*, IF (LOCATE(stories.language, ?) > 0, 1, 0) AS weight', [$languages])
                ->orderByDesc('weight');
            if ($before) {
                $query->where('id', '>', $before)->orderBy('id');
            } else if ($after) {
                $query->where('id', '<', $after)->orderByDesc('id');
            } else if ($first) {
                $query->where('id', '<=', $first)->orderByDesc('id');
            } else {
                $query->orderByDesc('id');
            }

            $stories = $query->with(['song'])
                ->withCount(['likes', 'comments'])
                ->paginate($request->get('count', 15));
        }
        return StoryResource::collection($stories);
    }

    public function index(Request $request)
    {
        static $cdn = false;
        if ($cdn === false) {
            $cdn = config('fixtures.cdn_url');
        }

        $data = [];
        if(!empty($request->user())){
            
            $userStories = DB::select('select id,media_type,created_at, REPLACE(concat("'.Storage::cloud()->url('media').'", media), "media","") as media from stories where user_id = '.$request->user()->id.' and created_at >= "'. Carbon::now()->subHours(24)->toDateTimeString().'"');

            $data['user'][] = [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'username' => $request->user()->username,
                'photo' => $cdn ? $cdn . $request->user()->photo : Storage::cloud()->url($request->user()->photo),
                'stories' => !empty($userStories) ? $userStories : []
            ];

            $userFollowingids = DB::table('followers')->select(DB::raw('group_concat(following_id) as following_ids'))->where('follower_id', $request->user()->id)->first()->following_ids;

            if(!empty($userFollowingids)){

                $userFollowingDatas = DB::select('select id, name, username, photo from users where id in ('.$userFollowingids.')');
                $followingData = [];
                foreach($userFollowingDatas as $ufd){
                    $followingData = [
                        'id' => $ufd->id,
                        'name' => $ufd->name,
                        'username' => $ufd->username,
                        'photo' => $cdn ? $cdn . $ufd->photo : Storage::cloud()->url($ufd->photo),
                    ];
                    $userFollowingStories = DB::select('select id,media_type,created_at, REPLACE(concat("'.Storage::cloud()->url('media').'", media), "media","") as media from stories where user_id = '.$ufd->id.' and created_at >= "'. Carbon::now()->subHours(24)->toDateTimeString().'"');
                    if(!empty($userFollowingStories)){                
                        $followingData['stories'] = $userFollowingStories;
                        array_push($data['user'], $followingData);
                    }
                }
            }

            return $data;
        }
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'song' => ['nullable', 'integer', 'exists:songs,id'],
            'media_type' => ['nullable'],
            'media' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/jpg,video/mp4',
                'max:' . config('fixtures.upload_limits.story.media'),
            ],
            'screenshot' => [
                'file',
                'mimes:png',
                'max:' . config('fixtures.upload_limits.story.screenshot'),
            ],
            'preview' => [
                'file',
                'mimes:gif',
                'max:' . config('fixtures.upload_limits.story.preview'),
            ],
            'description' => ['nullable', 'string', 'max:300'],
            'language' => [
                'required',
                'string',
                Rule::in(array_keys(config('fixtures.languages'))),
            ],
            'private' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
            'duration' => ['required', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'required_with:location', 'numeric'],
            'longitude' => ['nullable', 'required_with:location', 'numeric'],
        ]);
        /** @var UploadedFile $video */
        $media = $data['media'];
        $name = Str::random(15) . '.' . $media->guessExtension();
        if($data['media_type'] == 0){
            $data['media'] = $media->storePubliclyAs('images', $name, config('filesystems.cloud'));
        }else{
            $data['media'] = $media->storePubliclyAs('videos', $name, config('filesystems.cloud'));
        }
        /** @var UploadedFile $preview */
        if(!empty($data['preview'])){
            $preview = $data['preview'];
            $name = Str::random(15) . '.' . $preview->guessExtension();
            $data['preview'] = $preview->storePubliclyAs('previews', $name, config('filesystems.cloud'));
        }
        /** @var UploadedFile $screenshot */
        if(!empty($data['screenshot'])){
            $screenshot = $data['screenshot'];
            $name = Str::random(15) . '.' . $screenshot->guessExtension();
            $data['screenshot'] = $screenshot->storePubliclyAs('screenshots', $name, config('filesystems.cloud'));
        }
        $data['private'] = $data['private'] ?? false;
        $data['comments'] = $data['comments'] ?? false;
        if (empty($data['location'])) {
            unset($data['location'], $data['latitude'], $data['longitude']);
        }

        /** @var User $user */
        $user = $request->user();
        /** @var Story $story */
        $story = $user->stories()->make($data);
        $story->song_id = $data['song'] ?? null;
        $story->approved = true;
	    $story->expire_date = Carbon::now()->addHour(24);
        $story->save();
        SendNotification::dispatch(
            __('notifications.posted_new_story.title', ['user' => $user->username]),
            __('notifications.posted_new_story.body'),
            null,
            $user,
            null,
            $story,
            true
        );
        if ($story->description) {
            dispatch(new FindMentionsHashtags($story, $story->description));
        }

        return StoryResource::make($story);
    }

    public function show(Story $story)
    {
        $resource = StoryResource::make($story);
        views($story)->record();
        return $resource;
    }

    public function update(Story $story, Request $request)
    {
        $data = $this->validate($request, [
            'description' => ['nullable', 'string', 'max:300'],
            'language' => [
                'required',
                'string',
                Rule::in(array_keys(config('fixtures.languages'))),
            ],
            'private' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'required_with:location', 'numeric'],
            'longitude' => ['nullable', 'required_with:location', 'numeric'],
        ]);
        $data['private'] = $data['private'] ?? false;
        $data['comments'] = $data['comments'] ?? false;
        if (empty($data['location'])) {
            unset($data['location'], $data['latitude'], $data['longitude']);
        }

        $story->fill($data);
        $retag = $story->isDirty('description');
        $story->save();
        if ($retag) {
            dispatch(new FindMentionsHashtags($story, $story->description, true));
        }

        return StoryResource::make($story);
    }

    public function destroy(Story $story)
    {
        $story->delete();
    }
}
