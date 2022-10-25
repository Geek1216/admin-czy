<?php

namespace App\Http\Livewire;

use App\Suggestion;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class SuggestionIndex extends Component
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
        $query = Suggestion::query();
        if ($this->search) {
            $query->whereHas('user', function (Builder $query) {
                $query->where('name', 'like', "%$this->search%")
                    ->orWhere('email', 'like', "%$this->search%")
                    ->orWhere('username', 'like', "%$this->search%");
            });
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $suggestions = $query->paginate($this->length);
        return view('livewire.suggestion-index', compact('suggestions'));
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
