<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Pushok\Client;

class ChannelTest extends TestCase
{
    protected $client;
    protected $notification;
    protected $channel;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->channel = new ApnChannel($this->client);
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
}

class TestNotification extends Notification
{
    public function toApn($notifiable)
    {
        return new ApnMessage('title');
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
        return (new ApnMessage('title'))->via($this->client);
    }
}
