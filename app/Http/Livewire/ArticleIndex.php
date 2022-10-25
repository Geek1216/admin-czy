<?php

namespace App\Http\Livewire;

use App\Article;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleIndex extends Component
{
    use WithPagination;

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $search;

    public $section;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = Article::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('title', 'like', "%$this->search%")
                    ->orWhere('source', 'like', "%$this->search%");
            });
        }

        if ($this->section) {
            $query->whereHas('sections', function (Builder $query) {
                $query->whereKey($this->section);
            });
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $articles = $query->paginate($this->length);
        return view('livewire.article-index', compact('articles'));
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

    public function updatingSection()
    {
        $this->resetPage();
    }
}
