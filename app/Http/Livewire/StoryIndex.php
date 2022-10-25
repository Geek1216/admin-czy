<?php

namespace App\Http\Livewire;

use App\Story;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class StoryIndex extends Component
{
    use WithPagination;

    public $search;

    public $length;

    public $section;

    public $language;

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

    public function updatingSection()
    {
        $this->resetPage();
    }

    public function updatingLanguage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Story::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('description', 'like', "%$this->search%")
                    ->orWhereHas('user', function (Builder $query) {
                        $query->where('name', 'like', "%$this->search%")
                            ->orWhere('email', 'like', "%$this->search%");
                    });
            });
        }

        if ($this->section) {
            $query->whereHas('sections', function (Builder $query) {
                $query->whereKey($this->section);
            });
        }

        if ($this->language) {
            $query->where('language', $this->language);
        }

        $stories = $query->latest()->paginate($this->length);
        return view('livewire.story-index', compact('story'));
    }
}
