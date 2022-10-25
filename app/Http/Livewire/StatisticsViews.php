<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Device;
use CyrildeWit\EloquentViewable\Support\Period;
use Livewire\Component;

class StatisticsViews extends Component
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
        return view('livewire.statistics-views');
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
        //     $this->current = views(Clip::class)
        //         ->period(Period::create($range[0][0], $range[0][1]))
        //         ->count();
        //     $this->previous = views(Clip::class)
        //         ->period(Period::create($range[1][0], $range[1][1]))
        //         ->count();
        // } else {
        //     $this->current = $this->previous = 0;
        // }
        $this->current = $this->previous = 0;
    }
}
