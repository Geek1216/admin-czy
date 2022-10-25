<?php

namespace App\Http\Livewire;

use App\Verification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class VerificationDestroy extends Component
{
    use AuthorizesRequests;

    public $verification;

    public function mount(Verification $verification)
    {
        $this->verification = $verification;
    }

    public function render()
    {
        return view('livewire.verification-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->verification->delete();
        flash()->info(__('Verification :name has been deleted.', ['name' => $this->verification->user->name]));
        $this->redirect(route('verifications.index'));
    }
}
