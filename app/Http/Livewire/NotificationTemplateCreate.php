<?php

namespace App\Http\Livewire;

use App\NotificationTemplate;
use Livewire\Component;

class NotificationTemplateCreate extends Component
{
    public $title;

    public $body;

    public function render()
    {
        return view('livewire.notification-template-create');
    }

    public function create()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
        ]);
        $template = NotificationTemplate::create($data);
        flash()->success(__('Notification template :title has been successfully added.', ['title' => $template->title_short]));
        $this->redirect(route('notification-templates.show', $template));
    }
}
