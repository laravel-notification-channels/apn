<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;

class ApnServiceProvider extends ServiceProvider
{
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
    }
}
