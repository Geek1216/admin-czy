<?php

namespace App\Http\Livewire;

use App\Level;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class LevelIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['order' => 'asc'];

    public $search;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Level::query();
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%");
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $levels = $query->paginate($this->length);
        return view('livewire.level-index', compact('levels'));
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
}
