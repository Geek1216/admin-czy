<?php

namespace App\Http\Livewire;

use App\Story;
use Livewire\Component;

class StoryShow extends Component
{
    public $story;

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function render()
    {
        $activities = $this->story->activities()->latest()->paginate();
        return view('livewire.story-show', compact('activities'));
    }
}
