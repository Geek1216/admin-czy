<?php

namespace App\Http\Livewire;

use App\Report;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ReportIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public $status = 'received';

    public $subject;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Report::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('reason', 'like', "%$this->search%")
                    ->orWhere('message', 'like', "%$this->search%");
            });
        }

        if ($this->subject) {
            $query->where('subject_type', $this->subject);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $reports = $query->paginate($this->length);
        return view('livewire.report-index', compact('reports'));
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

    public function updatingSubject()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }
}
