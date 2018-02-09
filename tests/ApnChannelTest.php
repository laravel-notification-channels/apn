<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnFeedback;
use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Client\Feedback as FeedbackClient;
use ZendService\Apple\Apns\Response\Message as MessageResponse;
use ZendService\Apple\Apns\Response\Feedback as FeedbackResponse;

class ChannelTest extends TestCase
{
    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \ZendService\Apple\Apns\Client\Feedback */
    protected $feedbackClient;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /** @var \Illuminate\Notifications\Notification */
    protected $notification;

    /** @var ApnChannel */
    protected $channel;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
        $this->feedbackClient = Mockery::mock(FeedbackClient::class);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->channel = new ApnChannel($this->client, $this->feedbackClient, $this->events, ApnChannel::SANDBOX, '/some/path', null);
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
    }

    public function tearDown()
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $message = $this->notification->toApn($this->notifiable);

        $responseOk = new MessageResponse();
        $responseOk->setCode(MessageResponse::RESULT_OK);

        $this->events->shouldNotReceive('fire');
        $this->client->shouldReceive('open')->once();
        $this->client->shouldReceive('send')->twice()->andReturn($responseOk);
        $this->client->shouldReceive('close')->once();

        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function it_fires_notification_failed_event_on_failure()
    {
        $message = $this->notification->toApn($this->notifiable);

        $responseFail = new MessageResponse();
        $responseFail->setCode(MessageResponse::RESULT_INVALID_TOKEN);

        $this->events->shouldReceive('fire')->twice();
        $this->client->shouldReceive('open')->once();
        $this->client->shouldReceive('send')->twice()->andReturn($responseFail);
        $this->client->shouldReceive('close')->once();

        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function it_can_receive_feedback()
    {
        $time = strtotime('now');
        $feedbackResponse = new FeedbackResponse();
        $feedbackResponse->setToken('abc123');
        $feedbackResponse->setTime($time);

        $this->client->shouldReceive('close');  // Close is called on destruct

        $this->feedbackClient->shouldReceive('open')->once();
        $this->feedbackClient->shouldReceive('feedback')->once()->andReturn([$feedbackResponse]);
        $this->feedbackClient->shouldReceive('close');

        $feedback = $this->channel->getFeedback();
        $this->assertCount(1, $feedback);

        $feedback = $feedback[0];
        $this->assertInstanceOf(ApnFeedback::class, $feedback);
        $this->assertEquals($time, $feedback->getTimestamp());
        $this->assertEquals('abc123', $feedback->getToken());
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
