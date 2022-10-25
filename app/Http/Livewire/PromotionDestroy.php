<?php

namespace App\Http\Livewire;

use App\Promotion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PromotionDestroy extends Component
{
    use AuthorizesRequests;

    public $promotion;

    public function mount(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    public function render()
    {
        return view('livewire.promotion-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->promotion->delete();
        flash()->info(__('Promotion :title has been deleted.', ['title' => $this->promotion->title]));
        $this->redirect(route('promotions.index'));
    }
}
