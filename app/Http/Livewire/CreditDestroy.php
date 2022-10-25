<?php

namespace App\Http\Livewire;

use App\Credit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreditDestroy extends Component
{
    use AuthorizesRequests;

    public $credit;

    public function mount(Credit $credit)
    {
        $this->credit = $credit;
    }

    public function render()
    {
        return view('livewire.credit-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->credit->delete();
        flash()->info(__('Credit :title has been deleted.', ['title' => $this->credit->title]));
        $this->redirect(route('credits.index'));
    }
}
