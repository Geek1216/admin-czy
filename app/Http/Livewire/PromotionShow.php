<?php

namespace App\Http\Livewire;

use App\Promotion;
use Livewire\Component;

class PromotionShow extends Component
{
    public $promotion;

    public function mount(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    public function render()
    {
        $activities = $this->promotion->activities()->latest()->paginate();
        return view('livewire.promotion-show', compact('activities'));
    }
}
