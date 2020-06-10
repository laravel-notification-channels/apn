<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Carbon;
use Pushok\Client;

class ClientFactory
{
    /**
     * The number of minutes to cache the client.
     *
     * @var int
     */
    const CACHE_MINUTES = 20;

    /**
     * The app instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The cache repository instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new factory instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(Container $app, Repository $cache)
    {
        $this->app = $app;
        $this->cache = $cache;
    }

    /**
     * Get an instance of the Pushok client, holding on to each in the cache for the given length of time.
     *
     * @return \Pushok\Client
     */
    public function instance(): Client
    {
        return $this->cache->remember(Client::class, Carbon::now()->addMinutes(static::CACHE_MINUTES), function () {
            return $this->app->make(Client::class);
        });
    }
}
