<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;

class UserShow extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $activities = $this->user->activities()->latest()->paginate();
        return view('livewire.user-show', compact('activities'));
    }
}
