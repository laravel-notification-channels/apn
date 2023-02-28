<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Pushok\AuthProvider\Certificate;
use Pushok\AuthProvider\Token;
use Pushok\AuthProviderInterface;
use Pushok\Client;

class ApnServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthProviderInterface::class, function ($app) {
            $options = Arr::except($app['config']['broadcasting.connections.apn'], 'production');

            return Arr::exists($options, 'certificate_path') ? Certificate::create($options) : Token::create($options);
        });

        $this->app->bind(Client::class, function ($app) {
            return new Client($app->make(AuthProviderInterface::class), $app['config']['broadcasting.connections.apn.production']);
        });
    }
}
