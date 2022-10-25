<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Rules\UserPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return UserResource::make($request->user());
    }

    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:' . config('fixtures.upload_limits.user.photo'),
                'dimensions:min_width=128,max_width:2048',
            ],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => ['required', 'string', 'regex:/^\w[\w.]+\w$/', 'min:3', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+\d+$/', 'max:15'],
            'bio' => ['nullable', 'string', 'max:300'],
            'password' => ['nullable', 'required_with:new_password', 'string', 'min:8', 'max:32', new UserPassword],
            'new_password' => ['nullable', 'string', 'min:8', 'max:32', 'confirmed'],
            'links' => ['nullable', 'array'],
            'links.*.type' => ['required', 'string', Rule::in(array_keys(config('fixtures.link_types')))],
            'links.*.url' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'required_with:location', 'numeric'],
            'longitude' => ['nullable', 'required_with:location', 'numeric'],
        ]);
        /** @var User $user */
        $user = $request->user();
        if (empty($data['photo'])) {
            unset($data['photo']);
        } else {
            /** @var UploadedFile $photo */
            $photo = $data['photo'];
            // $image = Image::make($data['photo'])->fit(256)->encode('png');
            $image = Image::make($data['photo'])->encode('png');
            $name = 'photos/' . Str::random(15) . '.' . $photo->guessExtension();
            Storage::cloud()->put($name, (string) $image, 'public');
            $data['photo'] = $name;
            $old_photo = $user->photo;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if (empty($data['location'])) {
            $data['location'] = $data['latitude'] = $data['longitude'] = null;
        }

        $user->fill($data);
        if ($email_changed = $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        if (!empty($old_photo)) {
            Storage::cloud()->delete($old_photo);
        }
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->enabled = false;
        $user->save();
    }

    public function destroyPhoto(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if (!empty($user->photo)) {
            Storage::cloud()->delete($user->photo);
            $user->photo = null;
            $user->save();
        }
    }
}
