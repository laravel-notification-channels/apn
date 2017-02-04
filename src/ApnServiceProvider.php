<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;

class ApnServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(ApnChannel::class)
            ->needs('$environment')
            ->give(config('broadcasting.connections.apn.environment'));

        $this->app->when(ApnChannel::class)
            ->needs('$certificate')
            ->give(config('broadcasting.connections.apn.certificate'));

        $this->app->when(ApnChannel::class)
            ->needs('$passPhrase')
            ->give(config('broadcasting.connections.apn.pass_phrase'));
    }
}
