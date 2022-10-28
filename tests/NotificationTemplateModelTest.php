<?php

namespace DH\NotificationTemplates\Tests;

use DH\NotificationTemplates\Models\NotificationTemplate;
use DH\NotificationTemplates\Tests\stubs\Notifications\InvoicePaid;

class NotificationTemplateModelTest extends TestCase
{
    /** @test */
    public function it_can_resolve_the_right_template_for_a_notification()
    {
        $template = $this->createNotificationTemplateForNotification(InvoicePaid::class);

        $notification = new InvoicePaid;

        $resolvedTemplate = NotificationTemplate::findForNotification($notification);

        $this->assertEquals($template->id, $resolvedTemplate->id);
    }
}