<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;

class ApnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function register()
    {
        $this->app->when(ApnChannel::class)
            ->needs('$environment')
            ->give(config('broadcasting.connections.apn.environment'))
            ->needs('$certificate')
            ->give(config('broadcasting.connections.apn.certificate'))
            ->needs('$passPhrase')
            ->give(config('broadcasting.connections.apn.pass_phrase'))
        ;
    }
}
