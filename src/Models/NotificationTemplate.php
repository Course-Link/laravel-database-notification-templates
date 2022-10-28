<?php

namespace DH\NotificationTemplates\Models;

use DH\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use DH\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class NotificationTemplate extends Model implements NotificationTemplateInterface
{
    protected $guarded = [];

    public function scopeForNotification(Builder $query, Notification $notification): Builder
    {
        return $query->where('notification', get_class($notification));
    }

    public static function findForNotification(Notification $notification)
    {
        $notificationTemplate = static::forNotification($notification)->first();

        if (!$notificationTemplate) {
            throw MissingNotificationTemplate::forNotification($notification);
        }

        return $notificationTemplate;
    }

    public function getNotification(): string
    {
        return $this->notification;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}