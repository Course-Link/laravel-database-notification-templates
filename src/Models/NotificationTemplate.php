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

    public function isSimpleMessage(): bool
    {
        return isset($this->template);
    }

    public function getSimpleMessageData(): array
    {
        return [
            'level' => $this->level,
            'subject' => $this->subject,
            'greeting' => $this->greeting,
            'salutation' => $this->salutation,
            'introLines' => $this->intro_lines,
            'outroLines' => $this->outro_lines,
            'actionText' => $this->action_text,
            'actionUrl' => $this->action_url,
            'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $this->actionUrl ?? ''),
        ];
    }
}