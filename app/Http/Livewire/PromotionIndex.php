<?php

namespace App\Http\Livewire;

use App\Promotion;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public $sticky;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Promotion::query();
        if ($this->search) {
            $query->where('title', 'like', "%$this->search%");
        }

        if ($this->sticky) {
            $query->where('sticky', $this->sticky === 'true');
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $promotions = $query->paginate($this->length);
        return view('livewire.promotion-index', compact('promotions'));
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

    public function updatingSticky()
    {
        $this->resetPage();
    }
}
