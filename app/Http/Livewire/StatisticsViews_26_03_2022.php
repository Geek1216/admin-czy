<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Device;
use CyrildeWit\EloquentViewable\Support\Period;
use Livewire\Component;

class StatisticsViews_26_03_2022 extends Component
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
        if ($this->mode === '1H') {
            $current = [
                now()->startOfHour(),
                now()->endOfHour(),
            ];
            $previous = [
                now()->startOfHour()->subHour(),
                now()->endOfHour()->subHour(),
            ];
        } else if ($this->mode === '1D') {
            $current = [
                now()->startOfDay(),
                now()->endOfDay(),
            ];
            $previous = [
                now()->startOfDay()->subDay(),
                now()->endOfDay()->subDay(),
            ];
        } else if ($this->mode === '1W') {
            $current = [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ];
            $previous = [
                now()->startOfWeek()->subWeek(),
                now()->endOfWeek()->subWeek(),
            ];
        } else if ($this->mode === '1M') {
            $current = [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ];
            $previous = [
                now()->startOfMonth()->subMonth(),
                now()->endOfMonth()->subMonth(),
            ];
        }
        if (isset($current) && isset($previous)) {
            $this->current = views(Clip::class)
                ->period(Period::create($current[0], $current[1]))
                ->count();
            $this->previous = views(Clip::class)
                ->period(Period::create($previous[0], $previous[1]))
                ->count();
        } else {
            $this->current = $this->previous = 0;
        }
    }
}
