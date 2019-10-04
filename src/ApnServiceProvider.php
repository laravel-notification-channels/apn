<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;
use Pushok\AuthProvider\Token;
use Pushok\Client;

class ApnServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(Token::class, function ($app) {
            return Token::create([
                'key_id' => $app['config']['broadcasting.connections.apn.key_id'],
                'team_id' => $app['config']['broadcasting.connections.apn.team_id'],
                'app_bundle_id' => $app['config']['broadcasting.connections.apn.app_bundle_id'],
                'private_key_path' => $app['config']['broadcasting.connections.apn.private_key_path'],
                'private_key_secret' => $app['config']['broadcasting.connections.apn.private_key_secret'],
            ]);
        });

        $this->app->bind(Client::class, function ($app) {
            $production = $app['config']['broadcasting.connections.apn.environment'] === ApnChannel::PRODUCTION;

            return new Client($app->make(Token::class), $production);
        });
    }
}
