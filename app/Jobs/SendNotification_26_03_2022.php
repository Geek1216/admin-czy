<?php

namespace App\Jobs;

use App\Clip;
use App\Story;
use App\Device;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $body;

    private $clip;
    
    private $story;

    private $data;

    private $followers;

    private $title;

    private $user;

    public function __construct(
        string $title,
        string $body,
        ?array $data = null,
        ?User $user = null,
        ?Clip $clip = null,
        ?Story $story = null,
        bool $followers = false
    ) {
        $this->body = $body;
        $this->clip = $clip;
        $this->story = $story;
        $this->data = $data;
        $this->followers = $followers;
        $this->title = $title;
        $this->user = $user;
    }

    public function handle()
    {
        $message = CloudMessage::new();
        if ($this->user && $this->followers) {
            $recipients = Device::query()
                ->whereHas('user', function (Builder $query) {
                    $query->whereHas('followings', function (Builder $query) {
                        $query->whereKey($this->user->id);
                    });
                })
                ->where('push_service', 'fcm');
        } else if ($this->user) {
            $recipients = $this->user->devices()
                ->where('push_service', 'fcm');
        } else {
            $message = $message->withTarget('topic', config('services.firebase.topic'));
        }
        if ($this->data) {
            $data = [];
            foreach ($this->data as $key => $value) {
                $data[$key] = (string)$value;
            }
            $message = $message->withData($data);
        }
        $notification = Notification::create($this->title, $this->body);
        if ($this->clip) {
            $notification = $notification->withImageUrl(Storage::cloud()->url($this->clip->screenshot));
            $message = $message->withData(['clip' => (string)$this->clip->id]);
        }
        if ($this->story) {
            $notification = $notification->withImageUrl(Storage::cloud()->url($this->story->screenshot));
            $message = $message->withData(['story' => (string)$this->story->id]);
        }
        $message = $message->withNotification($notification);
        /** @var Messaging $messaging */
        $messaging = app('firebase.messaging');
        if (isset($recipients)) {
            $recipients->chunk(499, function (Collection $ids) use ($message, $messaging) {
                $messaging->sendMulticast($message, $ids->pluck('push_token')->toArray());
            });
        } else {
            $messaging->send($message);
        }
    }
}
