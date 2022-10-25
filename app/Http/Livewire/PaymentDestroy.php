<?php

namespace App\Http\Livewire;

use App\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PaymentDestroy extends Component
{
    use AuthorizesRequests;

    public $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function render()
    {
        return view('livewire.payment-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->payment->delete();
        flash()->info(__(
            'Payment :reference has been deleted.',
            ['reference' => $this->payment->reference_short]
        ));
        $this->redirect(route('payments.index'));
    }
}
