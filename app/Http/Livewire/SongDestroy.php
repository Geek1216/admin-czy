<?php

namespace App\Http\Livewire;

use App\Song;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SongDestroy extends Component
{
    use AuthorizesRequests;

    public $song;

    public function mount(Song $song)
    {
        $this->song = $song;
    }

    public function render()
    {
        return view('livewire.song-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->song->sections()->detach();
        $this->song->delete();
        flash()->info(__('Song :title has been deleted.', ['title' => $this->song->title]));
        $this->redirect(route('songs.index'));
    }
}
