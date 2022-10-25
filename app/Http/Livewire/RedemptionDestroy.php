<?php

namespace App\Http\Livewire;

use App\Redemption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class RedemptionDestroy extends Component
{
    use AuthorizesRequests;

    public $redemption;

    public function mount(Redemption $redemption)
    {
        $this->redemption = $redemption;
    }

    public function render()
    {
        return view('livewire.redemption-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->redemption->delete();
        flash()->info(__('Redemption #:id has been deleted.', ['id' => $this->redemption->id]));
        $this->redirect(route('redemptions.index'));
    }
}
