<?php

namespace App\Http\Livewire;

use App\NotificationTemplate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class NotificationTemplateDestroy extends Component
{
    use AuthorizesRequests;

    public $template;

    public function mount(NotificationTemplate $template)
    {
        $this->template = $template;
    }

    public function render()
    {
        return view('livewire.notification-template-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        $this->template->delete();
        flash()->info(__('Notification template :title has been deleted.', ['title' => $this->template->title_short]));
        $this->redirect(route('notification-templates.index'));
    }
}
