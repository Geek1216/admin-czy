<?php

namespace App\Http\Livewire;

use App\ClipSection;
use Livewire\Component;

class ClipSectionShow extends Component
{
    public $section;

    public function mount(ClipSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $activities = $this->section->activities()->latest()->paginate();
        return view('livewire.clip-section-show', compact('activities'));
    }
}
