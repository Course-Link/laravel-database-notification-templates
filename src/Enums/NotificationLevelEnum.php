<?php

namespace DH\NotificationTemplates\Enums;

enum NotificationLevelEnum: string
{
    case INFO = 'info';
    case SUCCESS = 'success';
    case ERROR = 'error';
}
