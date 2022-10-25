<?php

namespace App\Http\Livewire;

use App\StickerSection;
use Livewire\Component;

class StickerSectionUpdate extends Component
{
    public $section;

    public $name;

    public $order = 99;

    public function mount(StickerSection $section)
    {
        $this->section = $section;
        $this->fill($section);
    }

    public function render()
    {
        return view('livewire.sticker-section-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->section->fill($data);
        $this->section->save();
        flash()->info(__('Sticker section :name has been updated.', ['name' => $this->section->name]));
        $this->redirect(route('sticker-sections.show', $this->section));
    }
}
