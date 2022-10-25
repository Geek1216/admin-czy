<?php

namespace App\Observers;

use App\Group;
use Illuminate\Support\Facades\Storage;

class GroupObserver
{
    public function deleting(Group $group)
    {
    }

    public function deleted(Group $group)
    {
        Storage::cloud()->delete($group->thumbnail);
    }
}
