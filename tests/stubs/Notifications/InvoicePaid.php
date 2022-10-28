<?php

namespace DH\NotificationTemplates\Tests\stubs\Notifications;

use DH\NotificationTemplates\NotificationTemplateChannel;
use DH\NotificationTemplates\NotificationTemplateMessage;
use DH\NotificationTemplates\Traits\HasNotificationTemplate;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use HasNotificationTemplate;

    public function via($notifiable): array
    {
        return [NotificationTemplateChannel::class];
    }

    public function toMail($notifiable): NotificationTemplateMessage
    {
        return (new NotificationTemplateMessage())
            ->type($this->getTemplate());
    }
}