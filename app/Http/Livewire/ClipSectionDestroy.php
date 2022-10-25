<?php

namespace App\Http\Livewire;

use App\ClipSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ClipSectionDestroy extends Component
{
    use AuthorizesRequests;

    public $section;

    public function mount(ClipSection $section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.clip-section-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->section->delete();
        flash()->info(__('Clip section :name has been deleted.', ['name' => $this->section->name]));
        $this->redirect(route('clip-sections.index'));
    }
}
