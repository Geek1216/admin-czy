<?php

namespace App\Http\Livewire;

use App\Level;
use Livewire\Component;

class LevelShow extends Component
{
    public $level;

    public function mount(Level $level)
    {
        $this->level = $level;
    }

    public function render()
    {
        $activities = $this->level->activities()->latest()->paginate();
        return view('livewire.level-show', compact('activities'));
    }
}
