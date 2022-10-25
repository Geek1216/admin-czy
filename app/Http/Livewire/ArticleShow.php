<?php

namespace App\Http\Livewire;

use App\Article;
use Livewire\Component;

class ArticleShow extends Component
{
    public $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function render()
    {
        $activities = $this->article->activities()->latest()->paginate();
        return view('livewire.article-show', compact('activities'));
    }
}
