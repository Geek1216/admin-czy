<?php

namespace App\Observers;

use App\Clip;
use Illuminate\Support\Facades\Storage;

class ClipObserver
{
    public function deleting(Clip $clip)
    {
        $clip->comments()->delete();
        $clip->sections()->detach();
    }

    public function deleted(Clip $clip)
    {
        Storage::cloud()->delete($clip->video);
        Storage::cloud()->delete($clip->screenshot);
        Storage::cloud()->delete($clip->preview);
    }
}
