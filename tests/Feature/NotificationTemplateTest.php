<?php

use CourseLink\NotificationTemplates\Exceptions\MissingNotificationTemplate;
use CourseLink\NotificationTemplates\NotificationTemplateChannel;
use CourseLink\NotificationTemplates\Tests\Stubs\Notifiable\User;
use CourseLink\NotificationTemplates\Tests\Stubs\Notifications\InvoicePaid;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Mime\Email;

it('can render a notification', function () {
    $this->createNotificationTemplateForNotification(InvoicePaid::class);

    $notifiable = new User('1', 'test@example.com');

    $renderedNotification = (new InvoicePaid())->toMail($notifiable)->render();

    $this->assertStringContainsString('Your invoice has been paid!', $renderedNotification);
});

it('can send a notification', function () {
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
});

it('sets mail properties correctly', function () {
    $this->createNotificationTemplateForNotification(InvoicePaid::class);
    $notifiable = new User('1', 'test@example.com');
    $notifiable->notifyNow(new InvoicePaid());

    /** @var ArrayTransport $transport */
    $transport = app()->make('mailer')->getSymfonyTransport();
    $emails = $transport->messages();
    /** @var Email $email */
    $email = $emails->first()->getOriginalMessage();

    expect($notifiable->email)->toEqual($email->getTo()[0]->getAddress())
        ->and($emails)->toHaveCount(1);
    $this->assertStringContainsString('Your invoice has been paid!', $email->getHtmlBody());
});

it('it_throws_an_exception_if_no_template_exists_for_notification', function () {
    $this->expectException(MissingNotificationTemplate::class);

    $notifiable = new User('1', 'test@example.com');

    (new InvoicePaid())->toMail($notifiable)->render();
});