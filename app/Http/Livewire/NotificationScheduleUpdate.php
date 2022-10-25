<?php

namespace App\Http\Livewire;

use App\NotificationSchedule;
use App\NotificationTemplate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class NotificationScheduleUpdate extends Component
{
    public $schedule;

    public $template;

    public $time;

    public $clip;

    public function mount(NotificationSchedule $schedule)
    {
        $this->schedule = $schedule;
        $this->fill($schedule);
        $this->template = $schedule->template_id;
    }

    public function render()
    {
        $templates = NotificationTemplate::latest()->get();
        return view('livewire.notification-schedule-update', compact('templates'));
    }

    public function update()
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
        $this->schedule->fill($data);
        $this->schedule->template_id = $data['template'];
        $this->schedule->save();
        flash()->info(__('Notification schedule @ :time has been updated.', ['time' => $this->schedule->time]));
        $this->redirect(route('notification-schedules.show', $this->schedule));
    }
}
