<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Jobs\SendNotification;
use App\NotificationSchedule;
use App\NotificationTemplate;
use Livewire\Component;

class NotificationScheduleShow_26_03_2022 extends Component
{
    public $schedule;

    public function mount(NotificationSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function render()
    {
        $activities = $this->schedule->activities()->latest()->paginate();
        return view('livewire.notification-schedule-show', compact('activities'));
    }

    public function send()
    {
        if ($this->schedule->template) {
            $template = $this->schedule->template;
        } else {
            $template = NotificationTemplate::inRandomOrder()->first();
        }

        if (empty($template)) {
            session()->flash('warning', __('Please add at least 1 notification template.'));
        } else {
            $title = $template->title;
            if ($this->schedule->clips === 'latest') {
                $clip = Clip::latest()->first();
            } else if ($this->schedule->clips === 'random') {
                $clip = Clip::select('id')->inRandomOrder()->first();
                if ($clip) {
                    $clip = Clip::findOrFail($clip->id);
                }
            }

            if (isset($clip) && $clip->description) {
                $title = str_replace("\n", ' ', $clip->description);
            }

            $data = null;
            if (isset($clip)) {
                $data['clip'] = $clip->id;
            }

            dispatch(new SendNotification($title, $template->body, $data, null, $clip ?? null));
            activity()
                ->performedOn($this->schedule)
                ->log($this->schedule->getDescriptionForEvent('sent'));
            session()->flash('info', __('Notification will be sent shortly.'));
        }
    }
}
