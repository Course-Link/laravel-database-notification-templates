<?php

namespace DH\NotificationTemplates;

use DH\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Container\Container;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationTemplateMessage extends MailMessage
{
    public function __construct(
        public readonly NotificationTemplateInterface $template)
    {
    }

    public function render()
    {
        if ($this->template->getTemplate()) {
            $view = Container::getInstance()->make(NotificationTemplateView::class);
            return $view->make($this->template, $this->data())->render();
        }

        $markdown = Container::getInstance()->make(Markdown::class);

        return $markdown->theme($this->theme ?: $markdown->getTheme())
            ->render($this->markdown, $this->data());
    }
}