<?php

namespace NotificationChannels\Gcm\Test;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Apn\ApnChannel;
use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\ApnMessage;
use PHPUnit_Framework_TestCase;
use Mockery;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Response\Message as MessageResponse;

class ChannelTest extends PHPUnit_Framework_TestCase
{
    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /** @var \Illuminate\Notifications\Notification */
    protected $notification;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new ApnChannel($this->client, $this->events, ApnChannel::SANDBOX, '/some/path', null);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $message = $this->notification->toApn($this->notifiable);

        $message->title;
        $message->body;
        $message->custom;

        $responseOk = new MessageResponse();
        $responseOk->setCode(MessageResponse::RESULT_OK);

        $responseFail = new MessageResponse();
        $responseFail->setCode(MessageResponse::RESULT_INVALID_TOKEN);

        $this->events->shouldReceive('fire')->once();
        $this->client->shouldReceive('open')->once();
        $this->client->shouldReceive('send')->twice()->andReturn($responseOk, $responseFail);
        $this->client->shouldReceive('close')->once();

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
        return ['662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd8', '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd9'];
    }
}

class TestNotification extends Notification
{
    public function toApn($notifiable)
    {
        return new ApnMessage();
    }
}
