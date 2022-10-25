<?php

namespace App\Observers;

use App\Verification;
use Illuminate\Support\Facades\Storage;

class VerificationObserver
{
    public function deleted(Verification $verification)
    {
        Storage::cloud()->delete($verification->document);
    }
}
