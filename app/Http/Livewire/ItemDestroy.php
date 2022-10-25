<?php

namespace App\Http\Livewire;

use App\Item;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ItemDestroy extends Component
{
    use AuthorizesRequests;

    public $item;

    public function mount(Item $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        return view('livewire.item-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->item->delete();
        flash()->info(__('Item :name has been deleted.', ['name' => $this->item->name]));
        $this->redirect(route('items.index'));
    }
}
