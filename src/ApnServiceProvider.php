<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Apn\Console\ApnSendTestNotification;

class ApnServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ApnCredentials::class, function ($app) {
            $config = $app['config'];

            return new ApnCredentials(
                $config->get('broadcasting.connections.apn.environment'),
                $config->get('broadcasting.connections.apn.certificate'),
                $config->get('broadcasting.connections.apn.pass_phrase')
            );
        });

        $this->registerCommands();
    }

    /**
     * Register any commands that this package provides.
     *
     * @return void
     */
    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ApnSendTestNotification::class,
            ]);
        }
    }
}
