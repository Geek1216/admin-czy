<?php

namespace App\Http\Livewire;

use App\Song;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class SongIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public $section;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Song::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('title', 'like', "%$this->search%")
                    ->orWhere('artist', 'like', "%$this->search%")
                    ->orWhere('album', 'like', "%$this->search%");
            });
        }

        if ($this->section) {
            $query->whereHas('sections', function (Builder $query) {
                $query->whereKey($this->section);
            });
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $songs = $query->paginate($this->length);
        return view('livewire.song-index', compact('songs'));
    }

    /**
     * @param string $column
     * @param string|false $direction
     */
    public function sort(string $column, $direction)
    {
        if ($direction) {
            $this->order[$column] = $direction;
        } else {
            unset($this->order[$column]);
        }

        $this->resetPage();
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
}
