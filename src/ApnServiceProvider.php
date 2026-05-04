<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Pushok\AuthProvider\Certificate;
use Pushok\AuthProvider\Token;
use Pushok\AuthProviderInterface;
use Pushok\Client;

class ApnServiceProvider extends ServiceProvider
{
    private const int CACHE_MINUTES = 20;

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

            $jwt = $cache->get(Token::class);
            if ($jwt !== null) {
                return Token::useExisting($jwt, $options);
            }

            return tap(
                Token::create($options),
                fn (Token $token) => $cache->put(Token::class, $token->get(), Carbon::now()->addMinutes(self::CACHE_MINUTES))
            );
        });

        $this->app->scoped(Client::class, function ($app) {
            return new Client(
                $app->make(AuthProviderInterface::class),
                $app['config']['broadcasting.connections.apn.production'],
            );
        });
    }
}
