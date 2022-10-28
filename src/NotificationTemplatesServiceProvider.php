<?php

namespace DH\NotificationTemplates;

use Illuminate\Support\ServiceProvider;

class NotificationTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
    }
}