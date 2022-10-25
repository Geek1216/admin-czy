<?php

namespace App\Http\Livewire;

use App\StorySection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StorySectionDestroy extends Component
{
    use AuthorizesRequests;

    public $section;

    public function mount(StorySection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.story-section-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->section->delete();
        session()->flash('info', __('Story section :name has been deleted.', ['name' => $this->section->name]));
        return redirect()->route('story-sections.index');
    }
}
