<?php

namespace App\Http\Livewire;

use App\Credit;
use Livewire\Component;
use Livewire\WithPagination;

class CreditIndex extends Component
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
        $query = Credit::query();
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%")
                ->orWhere('description', 'like', "%$this->search%")
                ->orWhere('play_store_product_id', 'like', "%$this->search%");
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $credits = $query->paginate($this->length);
        return view('livewire.credit-index', compact('credits'));
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
