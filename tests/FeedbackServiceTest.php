<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnFeedback;
use NotificationChannels\Apn\FeedbackService;
use ZendService\Apple\Apns\Client\Feedback as Client;
use ZendService\Apple\Apns\Response\Feedback as FeedbackResponse;

class FeedbackServiceTest extends TestCase
{
    /** @var \ZendService\Apple\Apns\Client\Feedback */
    protected $client;

    /** @var \NotificationChannels\Apn\FeedbackService */
    protected $feedbackService;

    /** @var ApnChannel */
    protected $channel;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
        $this->feedbackService = new FeedbackService($this->client,  ApnChannel::SANDBOX, '/some/path', null);
    }

    /** @test */
    public function it_can_receive_feedback()
    {
        $time = strtotime('now');
        $feedbackResponse = new FeedbackResponse();
        $feedbackResponse->setToken('abc123');
        $feedbackResponse->setTime($time);

        $this->client->shouldReceive('open')->once();
        $this->client->shouldReceive('feedback')->once()->andReturn([$feedbackResponse]);
        $this->client->shouldReceive('close');

        $feedback = $this->feedbackService->get();
        $this->assertCount(1, $feedback);

        $feedback = $feedback[0];
        $this->assertInstanceOf(ApnFeedback::class, $feedback);
        $this->assertEquals($time, $feedback->getTimestamp());
        $this->assertEquals('abc123', $feedback->getToken());
    }
}
