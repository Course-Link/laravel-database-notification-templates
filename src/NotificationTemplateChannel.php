<?php

namespace CourseLink\NotificationTemplates;

use CourseLink\NotificationTemplates\Exceptions\InvalidNotification;
use CourseLink\NotificationTemplates\Interfaces\HasNotificationTemplateInterface;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\SentMessage;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Notification;

class NotificationTemplateChannel extends MailChannel
{
    public function __construct(
        MailFactory                  $mailer,
        NotificationTemplateMarkdown $markdown,
    )
    {
        parent::__construct($mailer, $markdown);
    }

    /**
     * @param mixed $notifiable
     * @param Notification $notification
     * @return SentMessage|null
     * @throws InvalidNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!($notification instanceof HasNotificationTemplateInterface)) {
            throw new InvalidNotification;
        }

        $message = $notification->toMail($notifiable);

        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        $this->mailer->mailer($message->mailer ?? null)->send(
            $this->buildView($message),
            array_merge($message->data(), $this->additionalMessageData($notification)),
            $this->messageBuilder($notifiable, $notification, $message)
        );

        return null;
    }

    /**
     * @param NotificationTemplateMessage $message
     * @return array
     */
    public function buildView($message): array
    {
        return [
            'html' => $this->markdown->render($message->notificationTemplate->getTemplate(), $message->data()),
            'text' => $this->markdown->renderText($message->markdown, $message->data()),
        ];
    }
}