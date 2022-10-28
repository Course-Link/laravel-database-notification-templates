<?php

namespace DH\NotificationTemplates\Exceptions;

use Exception;
use Illuminate\Notifications\Notification;

class MissingNotificationTemplate extends Exception
{
    public static function forNotification(Notification $notification): static
    {
        $notificationClass = class_basename($notification);

        return new static("No mail template exists for mailable `{$notificationClass}`.");
    }
}