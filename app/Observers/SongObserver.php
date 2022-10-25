<?php

namespace App\Observers;

use App\Song;
use Illuminate\Support\Facades\Storage;

class SongObserver
{
    public function deleting(Song $song)
    {
        $song->clips()->update(['song_id' => null]);
        $song->stories()->update(['song_id' => null]);
        $song->sections()->detach();
    }

    public function deleted(Song $song)
    {
        Storage::cloud()->delete($song->audio);
        if ($song->cover) {
            Storage::cloud()->delete($song->cover);
        }
    }
}
