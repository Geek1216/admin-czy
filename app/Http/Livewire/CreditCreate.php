<?php

namespace App\Http\Livewire;

use App\Credit;
use Livewire\Component;

class CreditCreate extends Component
{
    public $title;

    public $description;

    public $price = 100;

    public $value = 100;

    public $order = 99;

    public $play_store_product_id;

    public function render()
    {
        return view('livewire.credit-create');
    }

    public function create()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1024'],
            'price' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'value' => ['required', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'max:65535'],
            'play_store_product_id' => ['nullable', 'string', 'max:255'],
        ]);
        /** @var Credit $credit */
        $credit = Credit::create($data);
        flash()->success(__('Credit :title has been successfully added.', ['title' => $credit->title]));
        $this->redirect(route('credits.show', $credit));
    }
}
