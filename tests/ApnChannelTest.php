<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Mockery;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ClientFactory;
use Pushok\Client;
use Pushok\Response;

class ApnChannelTest extends TestCase
{
    protected $client;
    protected $factory;
    protected $events;
    protected $channel;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->factory = Mockery::mock(ClientFactory::class);
        $this->factory->shouldReceive('instance')->andReturn($this->client);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new ApnChannel($this->factory, $this->events);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->client->shouldReceive('addNotification');
        $this->client->shouldReceive('push')->once();

        $this->channel->send(new TestNotifiable, new TestNotification);
    }

    /** @test */
    public function it_can_send_a_notification_with_custom_client()
    {
        $customClient = Mockery::mock(Client::class);

        $this->client->shouldNotReceive('addNotification');

        $customClient->shouldReceive('addNotification');
        $customClient->shouldReceive('push')->once();

        $this->channel->send(new TestNotifiable, (new TestNotificationWithClient($customClient)));
    }

    /** @test */
    public function it_dispatches_events_for_failed_notifications()
    {
        $this->events->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->client->shouldReceive('addNotification');
        $this->client->shouldReceive('push')
            ->once()
            ->andReturn([
                new Response(200, 'headers', 'body'),
                new Response(400, 'headers', 'body'),
            ]);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }

    /** @test */
    public function it_dispatches_failed_notification_events_with_correct_channel()
    {
        $this->events->shouldReceive('dispatch')
            ->withArgs(function (NotificationFailed $notificationFailed) {
                return $notificationFailed->channel === ApnChannel::class;
            });

        $this->client->shouldReceive('addNotification');
        $this->client->shouldReceive('push')
            ->once()
            ->andReturn([
                new Response(200, 'headers', 'body'),
                new Response(400, 'headers', 'body'),
            ]);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }
}
