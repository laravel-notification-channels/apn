<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Mockery;
use NotificationChannels\Apn\ApnChannel;
use Pushok\Client;
use Pushok\Response;

class ApnChannelTest extends TestCase
{
    protected $client;
    protected $events;
    protected $notification;
    protected $channel;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new ApnChannel($this->client, $this->events);
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
}
