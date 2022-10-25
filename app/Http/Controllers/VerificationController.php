<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'document' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,pdf',
                'max:' . config('fixtures.upload_limits.verification.document'),
            ],
        ]);
        /** @var User $user */
        $user = $request->user();
        if ($user->verifications()->where('status', 'pending')->exists()) {
            throw ValidationException::withMessages([
                'document' => __('You already have a verification pending. Please wait for review.'),
            ]);
        } else if ($user->verified) {
            throw ValidationException::withMessages([
                'document' => __('Your account is already verified.'),
            ]);
        }
        /** @var UploadedFile $document */
        $document = $data['document'];
        $data['document'] = $document->storeAs(
            'documents',
            Str::random(15) . '.' . $document->extension(),
            config('filesystems.cloud')
        );
        $user->verifications()->create([
            'document' => $data['document'],
            'status' => 'pending',
        ]);
    }
}
