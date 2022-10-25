<?php

namespace App\Observers;

use App\Story;
use Illuminate\Support\Facades\Storage;

class StoryObserver
{
    public function deleting(Story $story)
    {
        $story->comments()->delete();
        $story->sections()->detach();
    }

    public function deleted(Story $story)
    {
        Storage::cloud()->delete($story->video);
        Storage::cloud()->delete($story->screenshot);
        Storage::cloud()->delete($story->preview);
    }
}
