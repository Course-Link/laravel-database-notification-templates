<?php

namespace CourseLink\NotificationTemplates\Traits;

use CourseLink\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use CourseLink\NotificationTemplates\Models\NotificationTemplate;

trait HasNotificationTemplate
{
    /**
     * @throws MissingNotificationTemplate
     */
    public function getTemplate()
    {
        return NotificationTemplate::findForNotification($this);
    }
}