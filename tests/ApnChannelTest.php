<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Mockery;
use NotificationChannels\Apn\ApnAdapter;
use NotificationChannels\Apn\ApnChannel;
use Pushok\Client;
use Pushok\Response;

class ApnChannelTest extends TestCase
{
    protected $client;

    protected $events;

    protected $channel;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);

        $container = new Container;
        $container->instance(Client::class, $this->client);

        $this->channel = new ApnChannel(
            $container,
            $this->events,
            new ApnAdapter,
        );
    }

    public function test_it_can_send_a_notification(): void
    {
        $this->client->shouldReceive('addNotification');
        $this->client->shouldReceive('push')->once();

        $this->channel->send(new TestNotifiable, new TestNotification);
    }

    public function test_it_can_send_a_notification_with_custom_client(): void
    {
        $customClient = Mockery::mock(Client::class);

        $this->client->shouldNotReceive('addNotification');

        $customClient->shouldReceive('addNotification');
        $customClient->shouldReceive('push')->once();

        $this->channel->send(
            new TestNotifiable,
            new TestNotificationWithClient($customClient),
        );
    }

    public function test_it_resolves_the_client_for_every_notification(): void
    {
        $clients = [];

        $container = new Container;
        $container->bind(Client::class, function () use (&$clients) {
            $client = Mockery::mock(Client::class);
            $client->shouldReceive('addNotification');
            $client->shouldReceive('push')->once();

            return $clients[] = $client;
        });

        $channel = new ApnChannel($container, $this->events, new ApnAdapter);

        $channel->send(new TestNotifiable, new TestNotification);
        $channel->send(new TestNotifiable, new TestNotification);

        $this->assertCount(2, $clients);
        $this->assertNotSame($clients[0], $clients[1]);
    }

    public function test_it_dispatches_events_for_failed_notifications(): void
    {
        $this->events
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->client->shouldReceive('addNotification');
        $this->client
            ->shouldReceive('push')
            ->once()
            ->andReturn([
                new Response(200, 'headers', 'body'),
                new Response(400, 'headers', 'body'),
            ]);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }

    public function test_it_dispatches_failed_notification_events_with_correct_channel(): void
    {
        $this->events
            ->shouldReceive('dispatch')
            ->withArgs(function (NotificationFailed $notificationFailed) {
                return $notificationFailed->channel === ApnChannel::class;
            });

        $this->client->shouldReceive('addNotification');
        $this->client
            ->shouldReceive('push')
            ->once()
            ->andReturn([
                new Response(200, 'headers', 'body'),
                new Response(400, 'headers', 'body'),
            ]);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }
}
