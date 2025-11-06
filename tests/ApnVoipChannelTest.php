<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Apn\ApnAdapter;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnMessagePushType;
use NotificationChannels\Apn\ApnVoipChannel;
use NotificationChannels\Apn\ClientFactory;
use Pushok\Client;
use Pushok\Response;

class ApnVoipChannelTest extends TestCase
{
    protected $client;

    protected $factory;

    protected $events;

    protected $channel;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->factory = Mockery::mock(ClientFactory::class);
        $this->factory->shouldReceive('instance')->andReturn($this->client);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new ApnVoipChannel(
            $this->factory,
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
}

class TestNotifiable
{
    use Notifiable;

    /**
     * @return array
     */
    public function routeNotificationForApn()
    {
        return [
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd8',
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd9',
        ];
    }

    /**
     * @return array
     */
    public function routeNotificationForApnVoip()
    {
        return [
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd1',
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd2',
        ];
    }
}

class TestNotification extends Notification
{
    public function toApn($notifiable)
    {
        return new ApnMessage('title');
    }

    public function toApnVoip($notifiable)
    {
        return new ApnMessage()->pushType(ApnMessagePushType::Voip);
    }
}

class TestNotificationWithClient extends Notification
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function toApn($notifiable)
    {
        return new ApnMessage('title')->via($this->client);
    }

    public function toApnVoip($notifiable)
    {
        return new ApnMessage()
            ->pushType(ApnMessagePushType::Voip)
            ->via($this->client);
    }
}
