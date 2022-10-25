<?php

namespace App\Http\Livewire;

use App\Sticker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StickerDestroy extends Component
{
    use AuthorizesRequests;

    public $sticker;

    public function mount(Sticker $sticker)
    {
        $this->sticker = $sticker;
    }

    public function render()
    {
        return view('livewire.sticker-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->sticker->delete();
        flash()->info(__('Sticker :image has been deleted.', ['image' => basename($this->sticker->image)]));
        $this->redirect(route('sticker-sections.show', $this->sticker->section));
    }
}
