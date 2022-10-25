<?php

namespace App\Http\Livewire;

use App\Payment;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public $status;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Payment::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('reference', 'like', "%$this->search%")
                    ->orWhereHas('user', function (Builder $query) {
                        $query->where('name', 'like', "%$this->search%")
                            ->orWhere('email', 'like', "%$this->search%")
                            ->orWhere('username', 'like', "%$this->search%");
                    });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $payments = $query->paginate($this->length);
        return view('livewire.payment-index', compact('payments'));
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

    public function updatingStatus()
    {
        $this->resetPage();
    }
}
