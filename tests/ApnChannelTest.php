<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use Pushok\Client;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Illuminate\Notifications\Notification;

class ChannelTest extends TestCase
{
    protected $client;
    protected $notification;
    protected $channel;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->channel = new ApnChannel($this->client);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $message = $this->notification->toApn($this->notifiable);

        $this->client->shouldReceive('addNotifications');
        $this->client->shouldReceive('push')->once();

        $this->channel->send($this->notifiable, $this->notification);
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
