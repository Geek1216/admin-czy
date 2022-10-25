<?php

namespace App\Http\Livewire;

use App\SongSection;
use Livewire\Component;

class SongSectionUpdate extends Component
{
    public $section;

    public $name;

    public $order = 99;

    public function mount(SongSection $section)
    {
        $this->section = $section;
        $this->fill($section);
    }

    public function render()
    {
        return view('livewire.song-section-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->section->fill($data);
        $this->section->save();
        flash()->info(__('Song section :name has been updated.', ['name' => $this->section->name]));
        $this->redirect(route('song-sections.show', $this->section));
    }
}
