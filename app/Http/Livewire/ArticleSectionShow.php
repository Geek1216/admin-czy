<?php

namespace App\Http\Livewire;

use App\ArticleSection;
use App\Jobs\FetchNewsArticles;
use Livewire\Component;

class ArticleSectionShow extends Component
{
    public $section;

    public function mount(ArticleSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        $activities = $this->section->activities()->latest()->paginate();
        return view('livewire.article-section-show', compact('activities'));
    }

    public function crawl($id)
    {
        dispatch(new FetchNewsArticles((array)$id));
        flash()->info(__('This section will soon be processed for new articles.'));
    }
}
