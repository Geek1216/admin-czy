<?php

namespace App\Http\Livewire;

use App\Redemption;
use Livewire\Component;

class RedemptionShow extends Component
{
    public $redemption;

    public function mount(Redemption $redemption)
    {
        $this->redemption = $redemption;
    }

    public function render()
    {
        $activities = $this->redemption->activities()->latest()->paginate();
        return view('livewire.redemption-show', compact('activities'));
    }
}
