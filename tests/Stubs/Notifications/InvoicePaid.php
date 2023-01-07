<?php

namespace CourseLink\NotificationTemplates\Tests\Stubs\Notifications;

use CourseLink\NotificationTemplates\Interfaces\HasNotificationTemplateInterface;
use CourseLink\NotificationTemplates\NotificationTemplateChannel;
use CourseLink\NotificationTemplates\NotificationTemplateMessage;
use CourseLink\NotificationTemplates\Traits\HasNotificationTemplate;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification implements HasNotificationTemplateInterface
{
    use HasNotificationTemplate;

    public function via($notifiable): array
    {
        return [NotificationTemplateChannel::class];
    }

    public function toMail($notifiable): NotificationTemplateMessage
    {
        return (new NotificationTemplateMessage())
            ->notificationTemplate($this->getTemplate());
    }
}