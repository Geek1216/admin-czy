<?php

namespace App\Observers;

use App\Sticker;
use Illuminate\Support\Facades\Storage;

class StickerObserver
{
    public function deleted(Sticker $sticker)
    {
        Storage::cloud()->delete($sticker->image);
    }
}
