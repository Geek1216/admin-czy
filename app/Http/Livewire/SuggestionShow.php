<?php

namespace App\Http\Livewire;

use App\Suggestion;
use Livewire\Component;

class SuggestionShow extends Component
{
    public $suggestion;

    public function mount(Suggestion $suggestion)
    {
        $this->suggestion = $suggestion;
    }

    public function render()
    {
        $activities = $this->suggestion->activities()->latest()->paginate();
        return view('livewire.suggestion-show', compact('activities'));
    }
}
