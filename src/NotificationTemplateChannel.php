<?php

namespace DH\NotificationTemplates;

use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Notification;

class NotificationTemplateChannel extends MailChannel
{
    protected NotificationTemplateView $view;

    public function __construct(
        MailFactory              $mailer,
        Markdown                 $markdown,
        NotificationTemplateView $view
    )
    {
        $this->view = $view;

        parent::__construct($mailer, $markdown);
    }

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMail($notifiable);

        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        $this->mailer->mailer($message->mailer ?? null)->send(
            $this->buildView($message),
            array_merge($message->data(), $this->additionalMessageData($notification)),
            $this->messageBuilder($notifiable, $notification, $message)
        );
    }

    /**
     * @param NotificationTemplateMessage $message
     * @return array
     * @throws \Throwable
     */
    public function buildView($message)
    {
        return [
            'html' => $this->view->make($message, $message->data())->render(),
            'text' => $this->markdown->renderText($message->markdown, $message->data()),
        ];
    }
}