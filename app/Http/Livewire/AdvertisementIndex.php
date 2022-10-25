<?php

namespace App\Http\Livewire;

use App\Advertisement;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class AdvertisementIndex extends Component
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
        $query = Advertisement::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('type', 'like', "%$this->search%")
                    ->orWhere('location', 'like', "%$this->search%")
                    ->orWhere('network', 'like', "%$this->search%")
                    ->orWhere('link', 'like', "%$this->search%");
            });
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $advertisements = $query->paginate($this->length);
        return view('livewire.advertisement-index', compact('advertisements'));
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
