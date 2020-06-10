<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Carbon;
use Mockery;
use NotificationChannels\Apn\ClientFactory;
use Pushok\Client;

class ClientFactoryTest extends TestCase
{
    protected $app;
    protected $cache;
    protected $factory;

    public function setUp(): void
    {
        $this->app = Mockery::mock(Container::class);
        $this->cache = Mockery::mock(Repository::class);
        $this->factory = new ClientFactory($this->app, $this->cache);
    }

    /** @test */
    public function it_returns_an_instance_through_the_cache()
    {
        $client = Mockery::mock(Client::class);

        // $this->app->shouldReceive('make')
        //     ->with(Client::class)
        //     ->andReturn($client);

        $this->cache->shouldReceive('remember')
            ->with(Client::class, Mockery::type(Carbon::class), Mockery::on(function () {
                return true;
            }))
            ->andReturn($client);

        $result = $this->factory->instance();

        $this->assertSame($client, $result);
    }
}
