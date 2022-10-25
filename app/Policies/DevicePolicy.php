<?php

namespace App\Policies;

use App\Device;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;


    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Device $device)
    {
        return $device->user->id === $user->id;
    }
}
