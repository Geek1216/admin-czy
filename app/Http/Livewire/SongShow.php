<?php

namespace App\Http\Livewire;

use App\Song;
use Livewire\Component;

class SongShow extends Component
{
    public $song;

    public function mount(Song $song)
    {
        $this->song = $song;
    }

    public function render()
    {
        $activities = $this->song->activities()->latest()->paginate();
        return view('livewire.song-show', compact('activities'));
    }
}
