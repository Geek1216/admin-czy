<?php

namespace App\Http\Livewire;

use App\Suggestion;
use Livewire\Component;

class SuggestionUpdate extends Component
{
    public $suggestion;

    public $order = 99;

    public function mount(Suggestion $suggestion)
    {
        $this->suggestion = $suggestion;
        $this->fill($suggestion);
    }

    public function render()
    {
        return view('livewire.suggestion-update');
    }

    public function update()
    {
        $data = $this->validate([
            'order' => ['required', 'integer', 'min:0'],
        ]);
        $this->suggestion->fill($data);
        $this->suggestion->save();
        flash()->info(__('Suggestion for :name has been updated.', ['name' => $this->suggestion->user->name]));
        $this->redirect(route('suggestions.show', $this->suggestion));
    }
}
