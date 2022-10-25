<?php

namespace App\Http\Livewire;

use App\ArticleSection;
use Livewire\Component;

class ArticleSectionUpdate extends Component
{
    public $section;

    public $name;

    public $google_news_topic;

    public $google_news_language;

    public $order = 99;

    public function mount(ArticleSection $section)
    {
        $this->section = $section;
        $this->fill($section);
    }

    public function render()
    {
        return view('livewire.article-section-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'google_news_topic' => ['nullable', 'string', 'max:255'],
            'google_news_language' => ['nullable', 'required_with:google_news_topic', 'string', 'size:2'],
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->section->fill($data);
        $this->section->save();
        flash()->info(__('Article section :name has been updated.', ['name' => $this->section->name]));
        $this->redirect(route('article-sections.show', $this->section));
    }
}
