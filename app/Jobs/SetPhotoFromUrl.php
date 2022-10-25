<?php

namespace App\Jobs;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SetPhotoFromUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;

    public $user;

    public function __construct(User $user, string $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function handle()
    {
        $sink = sys_get_temp_dir() . '/' . Str::random(32);
        $response = (new Client())
            ->get($this->url, compact('sink'));
        if ($response->getStatusCode() === 200) {
            $image = Image::make($sink)->fit(256)->encode('png');
            $name = 'photos/' . Str::random(32) . '.png';
            Storage::cloud()->put($name, (string) $image, 'public');
            $this->user->photo = $name;
            $this->user->save();
        }

        unlink($sink);
    }
}
