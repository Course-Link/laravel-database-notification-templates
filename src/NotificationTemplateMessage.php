<?php

namespace CourseLink\NotificationTemplates;

use CourseLink\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use CourseLink\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Container\Container;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class NotificationTemplateMessage extends MailMessage
{
    public $view;
    public $markdown = 'notifications::email';
    public NotificationTemplateInterface $notificationTemplate;
    public $viewData = [];

    public function notificationTemplate(NotificationTemplateInterface $template, array $data = []): self
    {
        $this->notificationTemplate = $template;
        $this->subject = $template->getSubject();
        $this->viewData = $data;

        return $this;
    }

    public function render(): HtmlString
    {
        /** @var NotificationTemplateMarkdown $markdown */
        $markdown = app(NotificationTemplateMarkdown::class);

        return $markdown->render($this->notificationTemplate->getTemplate(), $this->data());
    }
}