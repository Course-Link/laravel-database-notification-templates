<?php

namespace DH\NotificationTemplates;

use DH\NotificationTemplates\Interfaces\NotificationTemplateInterface;
use Illuminate\Container\Container;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationTemplateMessage extends MailMessage
{
    public NotificationTemplateInterface $template;

    public function type(NotificationTemplateInterface $template, array $data = []): self
    {
        $this->template = $template;
        $this->viewData = $data;

        return $this;
    }

    public function render()
    {
        /** @var NotificationTemplateView $view */
        $view = Container::getInstance()->make(NotificationTemplateView::class);

        return $view->make($this->template, $this->data())->render();
    }
}