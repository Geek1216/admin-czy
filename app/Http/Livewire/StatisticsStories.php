<?php

namespace App\Http\Livewire;

use App\Story;
use Livewire\Component;

class StatisticsStories extends Component
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
        return view('livewire.statistics-story');
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
            $this->current = Story::whereBetween('created_at', $current)->count();
            $this->previous = Story::whereBetween('created_at', $previous)->count();
        } else {
            $this->current = $this->previous = 0;
        }
    }
}
