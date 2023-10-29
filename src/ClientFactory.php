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
     * Create a new factory instance.
     */
    public function __construct(protected Container $app, protected Repository $cache)
    {
        //
    }

    /**
     * Get an instance of the Pushok client, holding on to each in the cache for the given length of time.
     */
    public function instance(): Client
    {
        return $this->cache->remember(Client::class, Carbon::now()->addMinutes(static::CACHE_MINUTES), function () {
            return $this->app->make(Client::class);
        });
    }
}
