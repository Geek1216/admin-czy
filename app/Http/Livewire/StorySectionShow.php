<?php

namespace App\Http\Livewire;

use App\StorySection;
use Livewire\Component;

class StorySectionShow extends Component
{
    public $section;

    public function mount(StorySection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $activities = $this->section->activities()->latest()->paginate();
        return view('livewire.story-section-show', compact('activities'));
    }
}
