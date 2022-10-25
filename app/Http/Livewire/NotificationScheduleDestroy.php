<?php

namespace App\Http\Livewire;

use App\NotificationSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class NotificationScheduleDestroy extends Component
{
    use AuthorizesRequests;

    public $schedule;

    public function mount(NotificationSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function render()
    {
        return view('livewire.notification-schedule-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->schedule->delete();
        flash()->info(__('Notification schedule @ :time has been deleted.', ['time' => $this->schedule->time]));
        $this->redirect(route('notification-schedules.index'));
    }
}
