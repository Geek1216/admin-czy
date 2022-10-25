<?php

namespace App\Http\Livewire;

use App\NotificationSchedule;
use App\NotificationTemplate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class NotificationScheduleCreate extends Component
{
    public $template;

    public $time;

    public $clip;

    public function render()
    {
        $templates = NotificationTemplate::latest()->get();
        return view('livewire.notification-schedule-create', compact('templates'));
    }

    public function create()
    {
        $data = $this->validate([
            'template' => ['nullable', 'integer', 'exists:notification_templates,id'],
            'time' => ['required', 'string', 'date_format:H:i:s'],
            'clip' => ['nullable', 'string', Rule::in(array_keys(config('fixtures.notification_schedule_clips')))],
        ]);
        if (empty($data['template'])) {
            $data['template'] = null;
        }
        if (empty($data['clip'])) {
            $data['clip'] = null;
        }
        $schedule = NotificationSchedule::make($data);
        $schedule->template_id = $data['template'];
        $schedule->save();
        flash()->success(__('Notification schedule @ :time has been successfully added.', ['time' => $schedule->time]));
        $this->redirect(route('notification-schedules.show', $schedule));
    }
}
