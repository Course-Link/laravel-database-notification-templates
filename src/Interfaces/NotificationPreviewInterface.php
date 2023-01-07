<?php

namespace CourseLink\NotificationTemplates\Interfaces;

use CourseLink\NotificationTemplates\NotificationTemplateMessage;

interface NotificationPreviewInterface
{
    public static function preview(): NotificationTemplateMessage;
}