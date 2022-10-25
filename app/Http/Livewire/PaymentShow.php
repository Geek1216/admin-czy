<?php

namespace App\Http\Livewire;

use App\Payment;
use Livewire\Component;

class PaymentShow extends Component
{
    public $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function render()
    {
        $activities = $this->payment->activities()->latest()->paginate();
        return view('livewire.payment-show', compact('activities'));
    }
}
