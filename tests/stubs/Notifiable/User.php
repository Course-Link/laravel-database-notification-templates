<?php

namespace DH\NotificationTemplates\Tests\stubs\Notifiable;

use Illuminate\Notifications\Notifiable;

class User
{
    use Notifiable;

    public function __construct(
        public readonly string $key,
        public readonly string $email,
    )
    {
    }

    public function getKey(): int
    {
        return $this->key;
    }
}