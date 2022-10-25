<?php

namespace App\Observers;

use App\NotificationTemplate;

class NotificationTemplateObserver
{
    public function deleting(NotificationTemplate $template)
    {
        $template->schedules()->update(['template_id' => null]);
    }
}
