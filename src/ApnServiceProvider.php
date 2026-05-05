<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
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
            $config = $app->make(ConfigRepository::class);

            $options = Arr::except($config->get('broadcasting.connections.apn'), 'production');

            if (Arr::exists($options, 'certificate_path')) {
                return Certificate::create($options);
            }

            return $app->make(Token::class);
        });

        $this->app->bind(Token::class, function (Application $app) {
            $cache = $app->make(CacheRepository::class);
            $config = $app->make(ConfigRepository::class);

            $token = $cache->get(static::TOKEN_CACHE_KEY);
            $options = Arr::except($config->get('broadcasting.connections.apn'), 'production');

            if ($token) {
                return Token::useExisting($token, $options);
            }

            return tap(
                Token::create($options),
                fn (Token $token) => $cache->put(static::TOKEN_CACHE_KEY, $token->get(), now()->addMinutes(self::TOKEN_CACHE_MINUTES)),
            );
        });

        $this->app->scoped(Client::class, function (Application $app) {
            return new Client(
                $app->make(AuthProviderInterface::class),
                $app['config']['broadcasting.connections.apn.production'],
            );
        });
    }
}
