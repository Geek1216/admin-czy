<?php

namespace App\Http\Livewire;

use App\SongSection;
use Livewire\Component;

class SongSectionShow extends Component
{
    public $section;

    public function mount(SongSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $activities = $this->section->activities()->latest()->paginate();
        return view('livewire.song-section-show', compact('activities'));
    }
}
