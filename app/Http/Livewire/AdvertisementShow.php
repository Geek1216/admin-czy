<?php

namespace App\Http\Livewire;

use App\Advertisement;
use Livewire\Component;

class AdvertisementShow extends Component
{
    public $advertisement;

    public function mount(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    public function render()
    {
        $activities = $this->advertisement->activities()->latest()->paginate();
        return view('livewire.advertisement-show', compact('activities'));
    }
}
