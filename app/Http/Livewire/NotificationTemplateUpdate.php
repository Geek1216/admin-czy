<?php

namespace App\Http\Livewire;

use App\NotificationTemplate;
use Livewire\Component;

class NotificationTemplateUpdate extends Component
{
    public $template;

    public $title;

    public $body;

    public function mount(NotificationTemplate $template)
    {
        $this->template = $template;
        $this->fill($template);
    }

    public function render()
    {
        return view('livewire.notification-template-update');
    }

    public function update()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:255'],
        ]);
        $this->template->fill($data);
        $this->template->save();
        flash()->info(__('Notification template :title has been updated.', ['title' => $this->template->title_short]));
        $this->redirect(route('notification-templates.show', $this->template));
    }
}
