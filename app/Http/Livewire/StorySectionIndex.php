<?php

namespace App\Http\Livewire;

use App\StorySection;
use Livewire\Component;
use Livewire\WithPagination;

class StorySectionIndex extends Component
{
    use WithPagination;

    public $search;

    public $length;

    public function mount()
    {
        $this->length = '10';
    }

    public function updatingLength()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = StorySection::query();
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%");
        }

        $sections = $query->latest()->paginate($this->length);
        return view('livewire.story-section-index', compact('sections'));
    }
}
