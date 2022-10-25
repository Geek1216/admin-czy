<?php

namespace App\Http\Livewire;

use App\Clip;
use Livewire\Component;

class StatisticsClips extends Component
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
        return view('livewire.statistics-clips');
    }

    public function update($mode)
    {
        $this->mode = $mode;
        $this->refresh();
    }

    private function refresh()
    {
        // $range = get_range($this->mode);
        // if ($range) {
        //     $this->current = Clip::whereBetween('created_at', $range[0])->count();
        //     $this->previous = Clip::whereBetween('created_at', $range[1])->count();
        // } else {
        //     $this->current = $this->previous = 0;
        // }
        $this->current = $this->previous = 0;
    }
}
