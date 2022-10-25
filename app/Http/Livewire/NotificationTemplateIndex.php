<?php

namespace App\Http\Livewire;

use App\NotificationTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationTemplateIndex extends Component
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
        $query = NotificationTemplate::query();
        if ($this->search) {
            $query->where('title', 'like', "%$this->search%")
                ->orWhere('body', 'like', "%$this->search%");
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $templates = $query->paginate($this->length);
        return view('livewire.notification-template-index', compact('templates'));
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
