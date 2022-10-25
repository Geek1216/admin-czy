<?php

namespace App\Http\Controllers;

use App\Clip;
use App\Http\Resources\Clip as ClipResource;
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

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Clip::class);
    }

    /*public function index(Request $request)
    {*/
        /** @var Builder $query */
        // $query = Clip::query()
        /*->where('approved', true)
            ->where('media_type', 1)
            ->whereHas('user', function (Builder $query) {
                return $query->where('enabled', true);
            });
        /** @var User|null $user */
    /*    $user = $request->user();
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
                ->select('clips.id', DB::raw('RAND(' . $seed . ') as seed'), 'users.verified')
                ->join('users', 'clips.user_id', '=', 'users.id')
                ->take(999);
            $query->joinSub(
                $sub,
                '_',
                'clips.id',
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
                    WHEN clips.id = ? THEN 3
                    WHEN created_at >= ? AND _.verified = 1 THEN 2
                    WHEN created_at >= ? THEN 1
                    ELSE 0
                    END AS weight1,
                    IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight2',
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
                    IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight2',
                    [$seen, $seen, $languages]
                );
            }

            $clips = $query->with(['user', 'song'])
                ->withCount(['likes', 'comments'])
                ->orderByDesc('weight1')
                ->orderByDesc('weight2')
                ->orderByDesc('seed')
                ->paginate($request->get('count', 15));
        } else {
            $before = $request->get('before');
            $after = $request->get('after');
            $query = $query->selectRaw('*, IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight', [$languages])
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

            $clips = $query->with(['song'])
                ->withCount(['likes', 'comments'])
                ->paginate($request->get('count', 15));
        }

        return ClipResource::collection($clips);
    }*/

    public function index(Request $request)
    {
        /** @var Builder $query */
        $query = Clip::query()
            ->where('approved', true)
            ->where('media_type', 0)
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
                ->select('clips.id', DB::raw('RAND(' . $seed . ') as seed'), 'users.verified')
                ->join('users', 'clips.user_id', '=', 'users.id')
                ->take(999);
            $query->joinSub(
                $sub,
                '_',
                'clips.id',
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
                    WHEN clips.id = ? THEN 3
                    WHEN created_at >= ? AND _.verified = 1 THEN 2
                    WHEN created_at >= ? THEN 1
                    ELSE 0
                    END AS weight1,
                    IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight2',
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
                    IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight2',
                    [$seen, $seen, $languages]
                );
            }

            $clips = $query->with(['user', 'song'])
                ->withCount(['likes', 'comments'])
                ->orderByDesc('weight1')
                ->orderByDesc('weight2')
                ->orderByDesc('seed')
                ->paginate($request->get('count', 15));
        } else {
            $before = $request->get('before');
            $after = $request->get('after');
            $query = $query->selectRaw('*, IF (LOCATE(clips.language, ?) > 0, 1, 0) AS weight', [$languages])
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

            $clips = $query->with(['song'])
                ->withCount(['likes', 'comments'])
                ->paginate($request->get('count', 15));
        }

        return ClipResource::collection($clips);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'song' => ['nullable', 'integer', 'exists:songs,id'],
            'media_type' => ['nullable'],
            'video' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/jpg,video/mp4',
                'max:' . config('fixtures.upload_limits.clip.video'),
            ],
            'screenshot' => [
                'file',
                'mimes:png',
                'max:' . config('fixtures.upload_limits.clip.screenshot'),
            ],
            'preview' => [
                'file',
                'mimes:gif',
                'max:' . config('fixtures.upload_limits.clip.preview'),
            ],
            'description' => ['nullable', 'string', 'max:300'],
            'language' => [
                'string',
                Rule::in(array_keys(config('fixtures.languages'))),
            ],
            'private' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'boolean'],
            'duration' => ['integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'required_with:location', 'numeric'],
            'longitude' => ['nullable', 'required_with:location', 'numeric'],
        ]);
        /** @var UploadedFile $video */
        $video = $data['video'];
        $name = Str::random(15) . '.' . $video->guessExtension();
        $data['video'] = $video->storePubliclyAs('videos', $name, config('filesystems.cloud'));
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
        /** @var Clip $clip */
        $clip = $user->clips()->make($data);
        $clip->song_id = $data['song'] ?? null;
        $clip->approved = true;
        $clip->save();
        SendNotification::dispatch(
            __('notifications.posted_new_clip.title', ['user' => $user->username]),
            __('notifications.posted_new_clip.body'),
            null,
            $user,
            $clip,
            null,
            true
        );
        if ($clip->description) {
            dispatch(new FindMentionsHashtags($clip, $clip->description));
        }

        return ClipResource::make($clip);
    }

    public function show(Clip $clip)
    {
        $resource = ClipResource::make($clip);
        views($clip)->record();
        return $resource;
    }

    public function update(Clip $clip, Request $request)
    {
        $data = $this->validate($request, [
            'description' => ['nullable', 'string', 'max:300'],
            'language' => [
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

        $clip->fill($data);
        $retag = $clip->isDirty('description');
        $clip->save();
        if ($retag) {
            dispatch(new FindMentionsHashtags($clip, $clip->description, true));
        }

        return ClipResource::make($clip);
    }

    public function destroy(Clip $clip)
    {
        $clip->delete();
    }
}
