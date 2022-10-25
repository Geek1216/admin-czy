<?php

namespace App\Http\Livewire;

use App\StorySection;
use Livewire\Component;

class StorySectionUpdate extends Component
{
    public $section;

    public $name;

    public $order = 99;

    public function mount(StorySection $section)
    {
        $this->section = $section;
        $this->fill($section);
    }

    public function render()
    {
        return view('livewire.story-section-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->section->fill($data);
        $this->section->save();
        session()->flash('info', __('Story section :name has been updated.', ['name' => $this->section->name]));
        return redirect()->route('story-sections.show', $this->section);
    }
}
