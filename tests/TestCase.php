<?php

namespace DH\NotificationTemplates\Tests;

use DH\NotificationTemplates\Models\NotificationTemplate;
use DH\NotificationTemplates\NotificationTemplatesServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            NotificationTemplatesServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function createNotificationTemplateForNotification(
        string  $notification,
        ?string $notificationTemplate = null,
        array   $attributes = [],
    )
    {
        $notificationTemplate = $notificationTemplate ?? NotificationTemplate::class;

        return $notificationTemplate::create(array_merge([
            'notification' => $notification,
            'template' => file_get_contents(__DIR__ . '/stubs/Templates/invoice.blade.php'),
        ], $attributes));
    }
}