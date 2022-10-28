<?php

namespace DH\NotificationTemplates\Traits;

use DH\NotificationTemplates\Models\NotificationTemplate;

trait HasNotificationTemplate
{
    public function getTemplate()
    {
        return NotificationTemplate::findForNotification($this);
    }
}