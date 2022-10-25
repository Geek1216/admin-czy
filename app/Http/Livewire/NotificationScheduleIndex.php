<?php

namespace App\Http\Livewire;

use App\NotificationSchedule;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationScheduleIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['time' => 'asc'];

    public $search;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = NotificationSchedule::query();
        if ($this->search) {
            $query->where('time', 'like', "%$this->search%");
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $schedules = $query->paginate($this->length);
        return view('livewire.notification-schedule-index', compact('schedules'));
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
