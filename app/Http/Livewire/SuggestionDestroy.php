<?php

namespace App\Http\Livewire;

use App\Suggestion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SuggestionDestroy extends Component
{
    use AuthorizesRequests;

    public $suggestion;

    public function mount(Suggestion $suggestion)
    {
        $this->suggestion = $suggestion;
    }

    public function render()
    {
        return view('livewire.suggestion-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->suggestion->delete();
        flash()->info(__('Suggestion for :name has been deleted.', ['name' => $this->suggestion->user->name]));
        $this->redirect(route('suggestions.index'));
    }
}
