<?php

namespace App\Http\Livewire;

use App\NotificationTemplate;
use Livewire\Component;

class NotificationTemplateShow extends Component
{
    public $template;

    public function mount(NotificationTemplate $template)
    {
        $this->template = $template;
    }

    public function render()
    {
        $activities = $this->template->activities()->latest()->paginate();
        return view('livewire.notification-template-show', compact('activities'));
    }
}
