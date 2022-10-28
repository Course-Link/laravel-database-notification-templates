<?php

namespace DH\NotificationTemplates\Tests;

use DH\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use DH\NotificationTemplates\Tests\stubs\Notifiable\User;
use DH\NotificationTemplates\Tests\stubs\Notifications\InvoicePaid;
use Illuminate\Validation\Rules\In;

class NotificationTemplateTest extends TestCase
{
    /** @test */
    public function it_can_render_a_notification()
    {
        $this->createNotificationTemplateForNotification(InvoicePaid::class);

        $notifiable = new User();

        $renderedNotification = (new InvoicePaid())->toMail($notifiable)->render();

        $this->assertStringContainsString('Your invoice has been paid!', $renderedNotification);
    }

    /** @test */
    public function it_throws_an_exception_if_no_template_exists_for_notification()
    {
        $this->expectException(MissingNotificationTemplate::class);

        $notifiable = new User();

        (new InvoicePaid())->toMail($notifiable)->render();
    }
}