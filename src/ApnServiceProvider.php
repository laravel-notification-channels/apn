<?php

namespace NotificationChannels\Apn;

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
     * The token cache key.
     */
    const string TOKEN_CACHE_KEY = 'apn.token';

    /**
     * The token cache length.
     */
    const int TOKEN_CACHE_MINUTES = 20;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthProviderInterface::class, function (Application $app) {
            $options = Arr::except($app['config']['broadcasting.connections.apn'], 'production');

            if (Arr::exists($options, 'certificate_path')) {
                return Certificate::create();
            }

            return $this->app->make(Token::class);
        });

        $this->app->bind(Token::class, function (Application $app) {
            $token = $app['cache']->get(static::TOKEN_CACHE_KEY);

            if ($token) {
                $options = Arr::except($app['config']['broadcasting.connections.apn'], 'production');

                return Token::useExisting($token, $options);
            }

            return tap(Token::create(), function (Token $token) use ($app) {
                $app['cache']->put(static::TOKEN_CACHE_KEY, $token->get(), now()->addMintues(self::TOKEN_CACHE_MINUTES));
            });
        });

        $this->app->scoped(Client::class, function (Application $app) {
            return new Client(
                $app->make(AuthProviderInterface::class),
                $app['config']['broadcasting.connections.apn.production'],
            );
        });
    }
}
