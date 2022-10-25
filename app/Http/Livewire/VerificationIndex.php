<?php

namespace App\Http\Livewire;

use App\Verification;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class VerificationIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $search;

    public $status;

    public $order = ['created_at' => 'desc'];

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Verification::query();
        if ($this->search) {
            $query->where('status', 'like', "%$this->search%")
                ->orWhereHas('user', function (Builder $query) {
                    $query->where('name', 'like', "%$this->search%")
                        ->orWhere('email', 'like', "%$this->search%")
                        ->orWhere('phone', 'like', "%$this->search%")
                        ->orWhere('username', 'like', "%$this->search%");
                });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $verifications = $query->with('user')->paginate($this->length);
        return view('livewire.verification-index', compact('verifications'));
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
