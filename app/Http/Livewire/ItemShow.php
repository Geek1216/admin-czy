<?php

namespace App\Http\Livewire;

use App\Item;
use Livewire\Component;

class ItemShow extends Component
{
    public $item;

    public function mount(Item $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        $activities = $this->item->activities()->latest()->paginate();
        return view('livewire.item-show', compact('activities'));
    }
}
