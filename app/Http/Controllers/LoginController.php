<?php

namespace App\Http\Controllers;

use App\Jobs\SetPhotoFromUrl;
use App\Mail\LoginOtp;
use App\User;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Auth;
use Laravel\Msg91\Facade as Msg91;

class LoginController extends Controller
{
    public function facebook(Request $request)
    {
        abort_if(empty(config('services.facebook.app_id')), 403);
        $data = $this->validate($request, [
            'token' => ['required', 'string'],
        ]);
        /** @var Facebook $fb */
        //$fb = app(Facebook::class);
		//dd($fb);
		$fb = new \Facebook\Facebook([
		  'app_id' => '200349068459905',
		  'app_secret' => '61b10d6d5676c81bbbb48df537262593',
		  //'default_graph_version' => 'v2.10',
		  'default_access_token' => $data['token'], // optional
		]);
        $result = $fb->get('/me?fields=name,email', $data['token']);
		//dd($result);
        $me = $result->getGraphUser();
        if (empty($me)) {
            return abort(401);
        }

        $existing = User::query()
            ->where('facebook_id', $me->getId());
        if ($email = $me->getEmail()) {
            $existing->orWhere('email', $email);
        }

        /** @var User $user */
        $user = $existing->first();
        $existing = true;
        if (empty($user)) {
            $existing = false;
            $user = User::query()->create([
                'name' => $me->getName(),
                'password' => Hash::make(Str::random(8)),
                'enabled' => true,
                'verified' => false,
                'username' => 'user' . Str::upper(Str::random(8)),
                'email' => $email ?: null,
                'facebook_id' => $me->getId(),
            ]);
        } else if (!$user->enabled) {
            abort(403);
        }

        if (empty($user->facebook_id)) {
            $user->facebook_id = $me->getId();
            $user->save();
        }

        if (empty($user->photo)) {
            dispatch(new SetPhotoFromUrl($user, "https://graph.facebook.com/{$me->getId()}/picture?type=square"));
        }

        $token = $user->createToken(config('app.name'))->plainTextToken;
        return response()->json(compact('token', 'existing'));
    }

    public function firebase(Request $request)
    {
        abort_if(config('fixtures.otp_service') !== 'firebase', 403);
        $data = $this->validate($request, [
            'token' => ['required', 'string'],
        ]);
        /** @var Auth $auth */
        $auth = app('firebase.auth');
        $token = $auth->verifyIdToken($data['token']);
        $uid = $token->getClaim('sub');
        /** @var Auth\UserRecord $ur */
        $ur = $auth->getUser($uid);
        if (empty($ur->phoneNumber)) {
            throw ValidationException::withMessages([
                'token' => __('This token is not valid.'),
            ]);
        }

        /** @var User $user */
        $user = User::query()
            ->where('phone', $ur->phoneNumber)
            ->first();
        $existing = true;
        if (empty($user)) {
            $existing = false;
            $user = User::query()->create([
                'name' => 'User',
                'password' => Hash::make(Str::random(8)),
                'enabled' => true,
                'verified' => false,
                'username' => 'user' . Str::upper(Str::random(8)),
                'phone' => $ur->phoneNumber,
            ]);
        } else if (!$user->enabled) {
            abort(403);
        }

        $token = $user->createToken(config('app.name'))->plainTextToken;
        return response()->json(compact('token', 'existing'));
    }

    public function google(Request $request)
    {
		
        abort_if(empty(config('services.google.client_id')), 403);
        $data = $this->validate($request, [
            'token' => ['required', 'string'],
        ]);
        /** @var \Google_Client $client */
        $client = app(\Google_Client::class);
        $payload = $client->verifyIdToken($data['token']);
        if (empty($payload)) {
            abort(401);
        }

        $existing = User::query()
            ->where('google_id', $payload['sub']);
        if ($email = $payload['email'] ?? null) {
            $existing->orWhere('email', $email);
        }

        /** @var User $user */
        $user = $existing->first();
        $existing = true;
        if (empty($user)) {
            $existing = false;
            $user = User::query()->create([
                'name' => $payload['name'],
                'password' => Hash::make(Str::random(8)),
                'enabled' => true,
                'verified' => false,
                'username' => 'user' . Str::upper(Str::random(8)),
                'email' => $email ?: null,
                'google_id' => $payload['sub'],
            ]);
        } else if (!$user->enabled) {
            abort(403);
        }

        if (empty($user->google_id)) {
            $user->google_id = $payload['sub'];
            $user->save();
        }

        if (empty($user->photo) && !empty($payload['picture'])) {
            dispatch(new SetPhotoFromUrl($user, $payload['picture']));
        }

        $token = $user->createToken(config('app.name'))->plainTextToken;
        return response()->json(compact('token', 'existing'));
    }

    public function email(Request $request)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'otp' => ['required', 'digits_between:4,6'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);
        $md5 = hash('md5', $data['email']);
        // if (!Cache::has('email_otp_' . $md5)) {
        //     throw ValidationException::withMessages([
        //         'otp' => __('Please generate a new OTP.'),
        //     ]);
        // }

        // $hash = Cache::get('email_otp_' . $md5);
        // if (empty($hash) || !Hash::check($data['otp'], $hash)) {
        //     throw ValidationException::withMessages([
        //         'otp' => __('auth.failed'),
        //     ]);
        // }

        // Cache::forget('email_otp_' . $md5);
        /** @var User $user */
        $user = User::query()
            ->where('email', $data['email'])
            ->first();
        $existing = true;
        if (empty($user)) {
            $existing = false;
            $user = User::query()->create([
                'name' => $data['name'] ?? 'User',
                'password' => Hash::make(Str::random(8)),
                'enabled' => true,
                'verified' => false,
                'username' => 'user' . Str::upper(Str::random(8)),
                'email' => $data['email'],
            ]);
        } else if (!$user->enabled) {
            abort(403);
        }

        $user->markEmailAsVerified();
        $token = $user->createToken(config('app.name'))->plainTextToken;
        return response()->json(compact('token', 'existing'));
    }

    public function emailOtp(Request $request)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        $md5 = hash('md5', $data['email']);
        if (!Cache::has('email_otp_' . $md5)) {
            $otp = (string) rand(100000, 999999);
            Cache::put('email_otp_' . $md5, Hash::make($otp), now()->addMinutes(5));
            Mail::to($data['email'])->send(new LoginOtp($otp));
        }

        $exists = User::query()->where('email', $data['email'])->exists();
        return response()->json(compact('exists'));
    }

    public function phone(Request $request)
    {
        abort_if(empty(config('fixtures.otp_service')) || config('fixtures.otp_service') === 'firebase', 403);
        $data = $this->validate($request, [
            'cc' => ['required', 'digits_between:1,5'],
            'phone' => ['required', 'digits_between:7,15'],
            'otp' => ['required', 'digits_between:4,6'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);
        $phone = sprintf('+%s%s', $data['cc'], $data['phone']);
        $verified = false;
        if (config('fixtures.otp_service') === 'msg91') {
            $verified = Msg91::verify($data['cc'] . $data['phone'], $data['otp']);
        } else if (config('fixtures.otp_service') === 'twilio') {
            $result = app('twilio.verify')
                ->verificationChecks
                ->create($data['otp'], ['to' => $phone]);
            $verified = $result->valid;
        }

        if (!$verified) {
            throw ValidationException::withMessages([
                'otp' => __('auth.failed'),
            ]);
        }

        /** @var User $user */
        $user = User::query()
            ->where('phone', $phone)
            ->first();
        $existing = true;
        if (empty($user)) {
            $existing = false;
            $user = User::query()->create([
                'name' => $data['name'] ?? 'User',
                'password' => Hash::make(Str::random(8)),
                'enabled' => true,
                'verified' => false,
                'username' => 'user' . Str::upper(Str::random(8)),
                'phone' => sprintf('+%s%s', $data['cc'], $data['phone']),
            ]);
        } else if (!$user->enabled) {
            abort(403);
        }

        $token = $user->createToken(config('app.name'))->plainTextToken;
        return response()->json(compact('token', 'existing'));
    }

    public function phoneOtp(Request $request)
    {
        abort_if(empty(config('fixtures.otp_service')) || config('fixtures.otp_service') === 'firebase', 403);
        $data = $this->validate($request, [
            'cc' => ['required', 'digits_between:1,5'],
            'phone' => ['required', 'digits_between:7,15'],
        ]);
        $phone = sprintf('+%s%s', $data['cc'], $data['phone']);
        if (config('fixtures.otp_service') === 'msg91') {
            Msg91::otp($data['cc'] . $data['phone']);
        } else if (config('fixtures.otp_service') === 'twilio') {
            app('twilio.verify')
                ->verifications
                ->create($phone, 'sms');
        }

        $exists = User::query()->where('phone', $phone)->exists();
        return response()->json(compact('exists'));
    }
}
