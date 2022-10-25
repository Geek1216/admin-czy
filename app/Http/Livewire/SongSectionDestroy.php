<?php

namespace App\Http\Livewire;

use App\SongSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SongSectionDestroy extends Component
{
    use AuthorizesRequests;

    public $section;

    public function mount(SongSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.song-section-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->section->delete();
        flash()->info(__('Song section :name has been deleted.', ['name' => $this->section->name]));
        $this->redirect(route('song-sections.index'));
    }
}
