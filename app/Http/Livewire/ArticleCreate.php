<?php

namespace App\Http\Livewire;

use App\Article;
use App\ArticleSection;
use Livewire\Component;

class ArticleCreate extends Component
{
    public $sections = [];

    public $title;

    public $snippet;

    public $image;

    public $link;

    public $source;

    public $published_at;

    public function render()
    {
        $article_sections = ArticleSection::orderBy('name')->get();
        return view('livewire.article-create', compact('article_sections'));
    }

    public function create()
    {
        $data = $this->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['required', 'integer', 'exists:article_sections,id'],
            'title' => ['required', 'string', 'max:255'],
            'snippet' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'url', 'max:255'],
            'link' => ['required', 'string', 'url', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'date_format:"Y-m-d H:i:s"'],
        ]);
        /** @var Article $article */
        $article = Article::create($data);
        $article->sections()->sync((array)($data['sections'] ?? null));
        flash()->success(__('Article :title has been successfully added.', ['title' => $article->title_short]));
        $this->redirect(route('articles.show', $article));
    }
}
