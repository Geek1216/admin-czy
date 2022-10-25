<?php

namespace App\Http\Livewire;

use App\ArticleSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ArticleSectionDestroy extends Component
{
    use AuthorizesRequests;

    public $section;

    public function mount(ArticleSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.article-section-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->section->articles()->detach();
        $this->section->delete();
        flash()->info(__('Article section :name has been deleted.', ['name' => $this->section->name]));
        $this->redirect(route('article-sections.index'));
    }
}
