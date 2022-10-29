<?php

namespace DH\NotificationTemplates\Interfaces;

interface NotificationTemplateInterface
{
    public function getNotification(): string;

    public function getSubject(): string;

    public function getTemplate(): string;

    public function isSimpleMessage(): bool;

    public function getSimpleMessageData(): array;
}