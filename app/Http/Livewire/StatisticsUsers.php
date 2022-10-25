<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;

class StatisticsUsers extends Component
{
    public $mode = '1D';

    public $current = 0;

    public $previous = 0;

    public function mount()
    {
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.statistics-users');
    }

    public function update($mode)
    {
        $this->mode = $mode;
        $this->refresh();
    }

    private function refresh()
    {
        //$range = false;//get_range($this->mode);
        $this->current = $this->previous = 0;
        // if ($range) {
        //     $this->current = User::whereBetween('created_at', now()->startOfDay())->count();
        //     $this->previous = User::whereBetween('created_at', now()->endOfDay())->count();
        // } else {
           
        // }
    }
}
