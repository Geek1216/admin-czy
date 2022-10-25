<?php

namespace App\Http\Livewire;

use App\SongSection;
use Livewire\Component;

class SongSectionCreate extends Component
{
    public $name;

    public $order = 99;

    public function render()
    {
        return view('livewire.song-section-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $section = SongSection::create($data);
        flash()->success(__('Song section :name has been successfully added.', ['name' => $section->name]));
        $this->redirect(route('song-sections.show', $section));
    }
}
