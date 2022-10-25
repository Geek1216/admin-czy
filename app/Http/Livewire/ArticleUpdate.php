<?php

namespace App\Http\Livewire;

use App\Article;
use App\ArticleSection;
use Livewire\Component;

class ArticleUpdate extends Component
{
    public $article;

    public $sections = [];

    public $title;

    public $snippet;

    public $image;

    public $link;

    public $source;

    public $published_at;

    public function mount(Article $article)
    {
        $this->article = $article;
        $this->fill($article);
        $this->sections = $article->sections()->pluck('id')->toArray();
    }

    public function render()
    {
        $article_sections = ArticleSection::orderBy('name')->get();
        return view('livewire.article-update', compact('article_sections'));
    }

    public function update()
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
        $this->article->fill($data);
        $this->article->sections()->sync((array)($data['sections'] ?? null));
        $this->article->save();
        flash()->info(__('Article :title has been updated.', ['title' => $this->article->title_short]));
        $this->redirect(route('articles.show', $this->article));
    }
}
