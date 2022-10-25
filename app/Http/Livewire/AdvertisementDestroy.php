<?php

namespace App\Http\Livewire;

use App\Advertisement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AdvertisementDestroy extends Component
{
    use AuthorizesRequests;

    public $advertisement;

    public function mount(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    public function render()
    {
        return view('livewire.advertisement-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->advertisement->delete();
        flash()->info(__('Advertisement #:id has been deleted.', ['id' => $this->advertisement->id]));
        $this->redirect(route('advertisements.index'));
    }
}
