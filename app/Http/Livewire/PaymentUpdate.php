<?php

namespace App\Http\Livewire;

use App\Payment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PaymentUpdate extends Component
{
    public $payment;

    public $status;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->fill($payment);
    }

    public function render()
    {
        return view('livewire.payment-update');
    }

    public function update()
    {
        $data = $this->validate([
            'status' => [
                'required',
                'string',
                Rule::in(array_keys(config('fixtures.payment_statuses'))),
            ],
        ]);
        $this->payment->fill($data);
        $this->payment->save();
        flash()->info(__(
            'Payment :reference has been updated.',
            ['reference' => $this->payment->reference_short]
        ));
        $this->redirect(route('payments.show', $this->payment));
    }
}
