<?php

namespace App\Http\Livewire;

use App\ArticleSection;
use Livewire\Component;

class ArticleSectionCreate extends Component
{
    public $name;

    public $google_news_topic;

    public $google_news_language;

    public $order = 99;

    public function render()
    {
        return view('livewire.article-section-create');
    }

    public function create()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'google_news_topic' => ['nullable', 'string', 'max:255'],
            'google_news_language' => ['nullable', 'required_with:google_news_topic', 'string', 'size:2'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $section = ArticleSection::create($data);
        flash()->success(__('Article section :name has been successfully added.', ['name' => $section->name]));
        $this->redirect(route('article-sections.show', $section));
    }
}
