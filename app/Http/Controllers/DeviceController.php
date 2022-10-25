<?php

namespace App\Http\Controllers;

use App\Device;
use App\User;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Device::class);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'platform' => ['required', 'string', 'in:android,ios'],
            'push_service' => ['required', 'string', 'in:fcm,apns'],
            'push_token' => ['required', 'string'],
        ]);
        /** @var User $user */
        $user = $request->user();
        $device = $user->devices()->create($data);
        return response()->json(['id' => $device->id]);
    }

    public function update(Request $request, Device $device)
    {
        $data = $this->validate($request, [
            'push_token' => ['required', 'string'],
        ]);
        if ($device->push_token !== $data['push_token']) {
            $device->fill($data);
            $device->save();
        } else {
            $device->touch();
        }
    }
}
