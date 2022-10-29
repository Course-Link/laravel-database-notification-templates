<?php

namespace DH\NotificationTemplates\Tests;

use DH\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use DH\NotificationTemplates\NotificationTemplateChannel;
use DH\NotificationTemplates\Tests\stubs\Notifiable\User;
use DH\NotificationTemplates\Tests\stubs\Notifications\InvoicePaid;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mime\Email;

class NotificationTemplateTest extends TestCase
{
    /** @test */
    public function it_can_render_a_notification()
    {
        $this->createNotificationTemplateForNotification(InvoicePaid::class);

        $notifiable = new User('1', 'test@example.com');

        $renderedNotification = (new InvoicePaid())->toMail($notifiable)->render();

        $this->assertStringContainsString('Your invoice has been paid!', $renderedNotification);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        Notification::fake();
        $this->createNotificationTemplateForNotification(InvoicePaid::class);

        $notifiable = new User('1', 'test@example.com');
        $notifiable->notify(new InvoicePaid());

        Notification::assertSentTo(
            [$notifiable],
            InvoicePaid::class,
            function ($notification, $channels) {
                $this->assertContains(NotificationTemplateChannel::class, $channels);
                $this->assertEquals(InvoicePaid::class, $notification::class);
                return true;
            }
        );
    }

    /** @test */
    public function it_sets_mail_properties_correctly()
    {
        $this->createNotificationTemplateForNotification(InvoicePaid::class);
        $notifiable = new User('1', 'test@example.com');
        $notifiable->notifyNow(new InvoicePaid());

        /** @var ArrayTransport $transport */
        $transport = app()->make('mailer')->getSymfonyTransport();
        $emails = $transport->messages();
        /** @var Email $email */
        $email = $emails->first()->getOriginalMessage();

        $this->assertEquals($notifiable->email, $email->getTo()[0]->getAddress());
        $this->assertStringContainsString('Your invoice has been paid!', $email->getHtmlBody());
        $this->assertCount(1, $emails);
    }

    /** @test */
    public function it_throws_an_exception_if_no_template_exists_for_notification()
    {
        $this->expectException(MissingNotificationTemplate::class);

        $notifiable = new User('1', 'test@example.com');

        (new InvoicePaid())->toMail($notifiable)->render();
    }
}