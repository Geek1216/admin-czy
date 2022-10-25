<?php

namespace App\Http\Livewire;

use App\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ReportDestroy extends Component
{
    use AuthorizesRequests;

    public $report;

    public function mount(Report $report)
    {
        $this->report = $report;
    }

    public function render()
    {
        return view('livewire.report-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->report->delete();
        flash()->info(__('Report #:id has been deleted.', ['id' => $this->report->id]));
        $this->redirect(route('reports.index'));
    }
}
