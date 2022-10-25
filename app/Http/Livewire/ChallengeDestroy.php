<?php

namespace App\Http\Livewire;

use App\Challenge;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ChallengeDestroy extends Component
{
    use AuthorizesRequests;

    public $challenge;

    public function mount(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    public function render()
    {
        return view('livewire.challenge-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->challenge->delete();
        flash()->info(__('Challenge :hashtag has been deleted.', ['hashtag' => $this->challenge->hashtag]));
        $this->redirect(route('challenges.index'));
    }
}
