<?php

namespace App\Http\Livewire;

use App\Verification;
use Livewire\Component;

class VerificationShow extends Component
{
    public $verification;

    public function mount(Verification $verification)
    {
        $this->verification = $verification;
    }

    public function render()
    {
        $activities = $this->verification->activities()->latest()->paginate();
        return view('livewire.verification-show', compact('activities'));
    }
}
