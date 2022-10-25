<?php

namespace App\Http\Livewire;

use App\StorySection;
use Livewire\Component;

class StorySectionCreate extends Component
{
    public $name;

    public $order = 99;

    public function render()
    {
        return view('livewire.story-section-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $section = StorySection::create($data);
        session()->flash('success', __('Story section :name has been successfully added.', ['name' => $section->name]));
        return redirect()->route('story-sections.show', $section);
    }
}
