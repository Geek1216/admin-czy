<?php

namespace App\Http\Livewire;

use App\Report;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReportUpdate extends Component
{
    public $report;

    public $status;

    public function mount(Report $report)
    {
        $this->report = $report;
        $this->fill($report);
    }

    public function render()
    {
        return view('livewire.report-update');
    }

    public function update()
    {
        $data = $this->validate([
            'status' => ['required', 'string', Rule::in(array_keys(config('fixtures.report_statuses')))],
        ]);
        $this->report->fill($data);
        $this->report->save();
        flash()->info(__('Report #:id has been updated.', ['id' => $this->report->id]));
        $this->redirect(route('reports.show', $this->report));
    }
}
