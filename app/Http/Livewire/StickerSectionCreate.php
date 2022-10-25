<?php

namespace App\Http\Livewire;

use App\StickerSection;
use Livewire\Component;

class StickerSectionCreate extends Component
{
    public $name;

    public $order = 99;

    public function render()
    {
        return view('livewire.sticker-section-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $section = StickerSection::create($data);
        flash()->success(__('Sticker section :name has been successfully added.', ['name' => $section->name]));
        $this->redirect(route('sticker-sections.show', $section));
    }
}
