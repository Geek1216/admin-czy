<?php

namespace App\Http\Livewire;

use App\Article;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ArticleDestroy extends Component
{
    use AuthorizesRequests;

    public $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.article-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->article->sections()->detach();
        $this->article->delete();
        flash()->info(__('Article :title has been deleted.', ['title' => $this->article->title_short]));
        $this->redirect(route('articles.index'));
    }
}
