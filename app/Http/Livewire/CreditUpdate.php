<?php

namespace App\Http\Livewire;

use App\Credit;
use Livewire\Component;

class CreditUpdate extends Component
{
    public $credit;

    public $title;

    public $description;

    public $price;

    public $value;

    public $order;

    public $play_store_product_id;

    public function mount(Credit $credit)
    {
        $this->credit = $credit;
        $this->fill($credit);
    }

    public function render()
    {
        return view('livewire.credit-update');
    }

    public function update()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'price' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'value' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'max:65535'],
            'play_store_product_id' => ['nullable', 'string', 'max:255'],
        ]);
        $this->credit->fill($data);
        $this->credit->save();
        flash()->info(__('Credit :title has been updated.', ['title' => $this->credit->title]));
        $this->redirect(route('credits.show', $this->credit));
    }
}
