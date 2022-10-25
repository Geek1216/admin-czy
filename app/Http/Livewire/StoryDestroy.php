<?php

namespace App\Http\Livewire;

use App\Story;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StoryDestroy extends Component
{
    use AuthorizesRequests;

    public $story;

    public function mount(Story $story)
    {
        $this->story = $story;
    }

    public function render()
    {
        return view('livewire.story-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->story->sections()->detach();
        $this->story->delete();
        session()->flash('info', __('Story #:id has been deleted.', ['id' => $this->story->id]));
        return redirect()->route('stories.index');
    }
}
