<?php

namespace App\Observers;

use App\Sticker;
use App\StickerSection;

class StickerSectionObserver
{
    public function deleting(StickerSection $section)
    {
        $section->stickers()->each(function (Sticker $sticker) {
            $sticker->delete();
        });
    }
}
