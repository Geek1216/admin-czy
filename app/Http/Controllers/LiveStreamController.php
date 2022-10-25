<?php

namespace App\Http\Controllers;

use App\Http\Resources\LiveStream as LiveStreamResource;
use App\Jobs\SendNotification;
use App\LiveStream;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveStreamController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(LiveStream::class, 'liveStream');
    }

    public function index(Request $request)
    {
        $query = LiveStream::query()
            ->where('status', 'streaming')
            ->where('ends_at', '>', now())
            ->whereHas('user', function (Builder $query) {
                $query->where('enabled', true);
            });
        /** @var User|null $user */
        $user = $request->user();
        if ($user) {
            $query->whereDoesntHave('user', function (Builder $query) use ($user) {
                $query->whereKey($user->getKey());
            });
            $query->where(function (Builder $query) use ($user) {
                $query->where('private', false)
                    ->orWhereHas('user', function (Builder $query) use ($user) {
                        $query->whereHas('followers', function (Builder $query) use ($user) {
                            $query->whereKey($user->getKey());
                        });
                    });
            });
        } else {
            $query->where('private', false);
        }
        $liveStreams = $query->latest()
            ->with('user')
            ->paginate($request->get('count', 15));
        return LiveStreamResource::collection($liveStreams);
    }

    public function store(Request $request)
    {
        $data1 = $this->validate($request, [
            'private' => ['sometimes', 'boolean'],
        ]);
        /** @var User $user */
        $user = $request->user();
        $existing = $user->liveStreams()
            ->where('ends_at', '>=', now())
            ->where('status', 'streaming')
            ->first();
        if ($existing) {
            return LiveStreamResource::make($existing);
        }
        $service = setting('live_streaming_service');
        switch ($service) {
            case 'agora':
                $channel = Str::uuid()->toString();
                $data2 = compact('channel');
                break;
            default:
                abort(501);
                break;
        }
        $liveStream = $user->liveStreams()
            ->create([
                'service' => $service,
                'private' => !empty($data1['private']),
                'ends_at' => now()->addMinutes(60),
                'data' => $data2 ?? null,
                'status' => 'streaming',
            ]);
        SendNotification::dispatch(
            __('notifications.started_live_stream.title', ['user' => $user->username]),
            __('notifications.started_live_stream.body'),
            ['status' => $liveStream->status],
            $user,
            $liveStream,
            true
        );
        return LiveStreamResource::make($liveStream);
    }

    public function show(LiveStream $liveStream)
    {
        return LiveStreamResource::make($liveStream);
    }

    public function touch(LiveStream $liveStream)
    {
        $this->authorize('view', $liveStream);
        $resource = LiveStreamResource::make($liveStream);
        views($liveStream)->record();
        return $resource;
    }

    public function join(LiveStream $liveStream, Request $request)
    {
        $this->authorize('view', $liveStream);
        /** @var User|null $user */
        $user = $request->user();
        $publisher = $user && $liveStream->user->getKey() === $user->getKey();
        switch ($liveStream->service) {
            case 'agora':
                $data = $this->validate($request, [
                    'uid' => ['required', 'integer'],
                ]);
                $tokenRtc = \RtcTokenBuilder::buildTokenWithUid(
                    setting('agora_app_id'),
                    setting('agora_app_certificate'),
                    $liveStream->data['channel'],
                    $data['uid'],
                    $publisher ? \RtcTokenBuilder::RolePublisher : \RtcTokenBuilder::RoleSubscriber,
                    $liveStream->ends_at->timestamp);
                if ($user) {
                    $tokenRtm = \RtmTokenBuilder::buildToken(
                        setting('agora_app_id'),
                        setting('agora_app_certificate'),
                        $user->getKey().'',
                        \RtmTokenBuilder::RoleRtmUser,
                        $liveStream->ends_at->timestamp);
                }
                return response()->json([
                    'channel' => $liveStream->data['channel'],
                    'token_rtc' => $tokenRtc,
                    'token_rtm' => $tokenRtm ?? null,
                ]);
        }
        abort(501);
    }

    public function destroy(LiveStream $liveStream)
    {
        $liveStream->status = 'ended';
        $liveStream->save();
        SendNotification::dispatch(
            __('notifications.stopped_live_stream.title', ['user' => $liveStream->user->username]),
            __('notifications.stopped_live_stream.body'),
            ['status' => $liveStream->status],
            $liveStream->user,
            $liveStream,
            true
        );
    }
}
