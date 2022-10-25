<?php

namespace App\Observers;

use App\Advertisement;
use Illuminate\Support\Facades\Storage;

class AdvertisementObserver
{
    public function deleted(Advertisement $advertisement)
    {
        Storage::cloud()->delete($advertisement->image);
    }
}
