<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use Illuminate\Events\Dispatcher;
use ZendService\Apple\Apns\Message;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Apn\ApnAdapter;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Response\Message as MessageResponse;

class ChannelTest extends TestCase
{
    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /** @var \Illuminate\Notifications\Notification */
    protected $notification;

    /** @var ApnChannel */
    protected $channel;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->adapter = Mockery::mock(ApnAdapter::class);
        $this->credentials = $this->getTestCredentials();
        $this->channel = new ApnChannel($this->client, $this->events, $this->adapter, $this->credentials);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $message = $this->notification->toApn($this->notifiable);

        $responseOk = new MessageResponse();
        $responseOk->setCode(MessageResponse::RESULT_OK);

        $this->adapter->shouldReceive('adapt')->andReturn(new Message);
        $this->events->shouldNotReceive('fire');
        $this->client->shouldReceive('open')->twice();
        $this->client->shouldReceive('send')->twice()->andReturn($responseOk);
        $this->client->shouldReceive('close')->twice();

        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function it_fires_notification_failed_event_on_failure()
    {
        $message = $this->notification->toApn($this->notifiable);

        $responseFail = new MessageResponse();
        $responseFail->setCode(MessageResponse::RESULT_INVALID_TOKEN);

        $this->adapter->shouldReceive('adapt')->andReturn(new Message);
        $this->events->shouldReceive('fire')->twice();
        $this->client->shouldReceive('open')->twice();
        $this->client->shouldReceive('send')->twice()->andReturn($responseFail);
        $this->client->shouldReceive('close')->twice();

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
        return new ApnMessage();
    }
}
