<?php

namespace App\Http\Livewire;

use App\Level;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class LevelDestroy extends Component
{
    use AuthorizesRequests;

    public $level;

    public function mount(Level $level)
    {
        $this->level = $level;
    }

    public function render()
    {
        return view('livewire.level-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->level->delete();
        flash()->info(__('Level :name has been deleted.', ['name' => $this->level->name]));
        $this->redirect(route('levels.index'));
    }
}
