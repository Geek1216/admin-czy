<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Jobs\SendNotification as SendNotificationJob;
use App\NotificationTemplate;
use App\User;
use Livewire\Component;

class SendNotification_26_03_2022 extends Component
{
    public $user;

    public $clip;

    public $title;

    public $body;

    public function mount(int $user = -1, int $clip = -1)
    {
        $this->user = $user;
        $this->clip = $clip;
        $this->refresh();
    }

    private function refresh()
    {
        $template = NotificationTemplate::inRandomOrder()->first();
        if ($template) {
            $this->title = $template->title;
            $this->body = $template->body;
        }
    }

    public function render()
    {
        return view('livewire.send-notification');
    }

    public function send()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($this->user);
        $clip = Clip::find($this->clip);
        $data2 = null;
        if ($clip) {
            $data2 = ['clip' => $clip->id];
        }

        dispatch(new SendNotificationJob($data['title'], $data['body'], $data2, $user, $clip));
        session()->flash('success', __('Notification will be sent shortly.'));
        $this->reset(['title', 'body']);
        $this->refresh();
    }
}
