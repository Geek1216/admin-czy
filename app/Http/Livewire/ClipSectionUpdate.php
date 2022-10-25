<?php

namespace App\Http\Livewire;

use App\ClipSection;
use Livewire\Component;

class ClipSectionUpdate extends Component
{
    public $section;

    public $name;

    public $order = 99;

    public function mount(ClipSection $section)
    {
        $this->section = $section;
        $this->fill($section);
    }

    public function render()
    {
        return view('livewire.clip-section-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->section->fill($data);
        $this->section->save();
        flash()->info(__('Clip section :name has been updated.', ['name' => $this->section->name]));
        $this->redirect(route('clip-sections.show', $this->section));
    }
}
