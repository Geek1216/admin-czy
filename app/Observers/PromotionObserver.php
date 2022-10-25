<?php

namespace App\Observers;

use App\Promotion;
use Illuminate\Support\Facades\Storage;

class PromotionObserver
{
    public function deleted(Promotion $promotion)
    {
        Storage::cloud()->delete($promotion->image);
    }
}
