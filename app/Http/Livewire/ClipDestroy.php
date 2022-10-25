<?php

namespace App\Http\Livewire;

use App\Clip;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ClipDestroy extends Component
{
    use AuthorizesRequests;

    public $clip;

    public function mount(Clip $clip)
    {
        $this->clip = $clip;
    }

    public function render()
    {
        return view('livewire.clip-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->clip->sections()->detach();
        $this->clip->delete();
        flash()->info(__('Clip #:id has been deleted.', ['id' => $this->clip->id]));
        $this->redirect(route('clips.index'));
    }
}
