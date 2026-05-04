<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->bind(AuthProviderInterface::class, function (Application $app) {
            $options = Arr::except(
                $app->get('config')['broadcasting.connections.apn'],
                'production',
            );

            if (Arr::has($options, 'certificate_path')) {
                return Certificate::create($options);
            }

            $cache = $app->get(Repository::class);

            return ($token = $cache->get(Token::class))
                ? Token::useExisting($token, $options)
                : Token::create($options);
        });

        $this->app->scoped(Client::class, function ($app) {
            return new Client(
                $app->make(AuthProviderInterface::class),
                $app['config']['broadcasting.connections.apn.production'],
            );
        });
    }
}
