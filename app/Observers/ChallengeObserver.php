<?php

namespace App\Observers;

use App\Challenge;
use Illuminate\Support\Facades\Storage;

class ChallengeObserver
{
    public function deleted(Challenge $challenge)
    {
        Storage::cloud()->delete($challenge->image);
    }
}
