<?php

namespace App\Http\Livewire;

use App\Credit;
use Livewire\Component;

class CreditShow extends Component
{
    public $credit;

    public function mount(Credit $credit)
    {
        $this->credit = $credit;
    }

    public function render()
    {
        $activities = $this->credit->activities()->latest()->paginate();
        return view('livewire.credit-show', compact('activities'));
    }
}
