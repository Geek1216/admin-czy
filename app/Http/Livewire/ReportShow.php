<?php

namespace App\Http\Livewire;

use App\Report;
use Livewire\Component;

class ReportShow extends Component
{
    public $report;

    public function mount(Report $report)
    {
        $this->report = $report;
    }

    public function render()
    {
        $activities = $this->report->activities()->latest()->paginate();
        return view('livewire.report-show', compact('activities'));
    }
}
