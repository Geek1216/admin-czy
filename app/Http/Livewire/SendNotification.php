<?php

namespace App\Http\Livewire;

use App\Clip;
use App\Jobs\SendNotification as SendNotificationJob;
use App\NotificationTemplate;
use App\User;
use Livewire\Component;

class SendNotification extends Component
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
        /** @var User|null $user */
        $user = User::query()->find($this->user);
        /** @var Clip|null $clip */
        $clip = Clip::query()->find($this->clip);
        dispatch(new SendNotificationJob(
            $data['title'],
            $data['body'],
            $clip ? ['clip' => $clip->id] : null,
            $user,
            $clip));
        flash()->success(__('Notification will be sent shortly.'));
        $this->redirect(url()->previous('/'));
    }
}
