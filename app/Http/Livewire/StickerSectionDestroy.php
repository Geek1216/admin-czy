<?php

namespace App\Http\Livewire;

use App\StickerSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StickerSectionDestroy extends Component
{
    use AuthorizesRequests;

    public $section;

    public function mount(StickerSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.sticker-section-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->section->delete();
        flash()->info(__('Sticker section :name has been deleted.', ['name' => $this->section->name]));
        $this->redirect(route('sticker-sections.index'));
    }
}
