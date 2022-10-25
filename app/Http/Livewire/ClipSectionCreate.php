<?php

namespace App\Http\Livewire;

use App\ClipSection;
use Livewire\Component;

class ClipSectionCreate extends Component
{
    public $name;

    public $order = 99;

    public function render()
    {
        return view('livewire.clip-section-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $section = ClipSection::create($data);
        flash()->success(__('Clip section :name has been successfully added.', ['name' => $section->name]));
        $this->redirect(route('clip-sections.show', $section));
    }
}
