<?php

namespace App\Http\Livewire;

use App\Item;
use Livewire\Component;
use Livewire\WithPagination;

class ItemIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Item::query();
        if ($this->search) {
            $query->where('name', 'like', "%$this->search%");
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $items = $query->paginate($this->length);
        return view('livewire.item-index', compact('items'));
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
