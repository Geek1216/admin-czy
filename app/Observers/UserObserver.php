<?php

namespace App\Observers;

use App\Clip;
use App\Story;
use App\Jobs\DeleteFilesInBulk;
use App\Report;
use App\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $files = $user->clips()->get(['video', 'screenshot', 'preview']);
        $allfiles = $user->stories()->get(['media', 'screenshot', 'preview']);
        $media = $allfiles->pluck('media')->toArray();
        $videos = $files->pluck('video')->toArray();
        $screenshots = $files->pluck('screenshot')->toArray();
        $previews = $files->pluck('preview')->toArray();
        $user->stories()->each(function (Story $story) {
            $story->delete();
        });
        $user->comments()->delete();
        $user->devices()->delete();
        $user->suggestion()->delete();
        $user->verifications()->delete();
        Report::where('user_id', $user->id)->delete();
        dispatch(new DeleteFilesInBulk($medis + $videos + $screenshots + $previews));
    }
}
