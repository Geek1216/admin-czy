<?php

namespace App\Http\Livewire;

use App\Comment;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class CommentIndex extends Component
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
        $query = Comment::query();
        if ($this->search) {
            $query->where('comment', 'line', "%$this->search%")
                ->orWhere(function (Builder $query) {
                    $query->where('comment', 'like', "%$this->search%")
                        ->orWhereHas('commentator', function (Builder $query) {
                            $query->where('name', 'like', "%$this->search%")
                                ->orWhere('email', 'like', "%$this->search%")
                                ->orWhere('username', 'like', "%$this->search%");
                        });
                });
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $comments = $query->paginate($this->length);
        return view('livewire.comment-index', compact('comments'));
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
