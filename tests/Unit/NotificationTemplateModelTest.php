<?php

use CourseLink\NotificationTemplates\Models\NotificationTemplate;
use CourseLink\NotificationTemplates\Tests\Stubs\Notifications\InvoicePaid;

it('can resolve the right template for a notification', function () {
    $template = $this->createNotificationTemplateForNotification(InvoicePaid::class);

    $notification = new InvoicePaid;

    $resolvedTemplate = NotificationTemplate::findForNotification($notification);

    expect($template->id)->toEqual($resolvedTemplate->id);
});