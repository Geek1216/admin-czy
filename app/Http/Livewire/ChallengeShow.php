<?php

namespace App\Http\Livewire;

use App\Challenge;
use Livewire\Component;

class ChallengeShow extends Component
{
    public $challenge;

    public function mount(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    public function render()
    {
        $activities = $this->challenge->activities()->latest()->paginate();
        return view('livewire.challenge-show', compact('activities'));
    }
}
