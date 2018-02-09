<?php

namespace NotificationChannels\Apn\Tests;

use NotificationChannels\Apn\ApnFeedback;

class ApnFeedbackTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $feedback = new ApnFeedback('token', 123);

        $this->assertEquals('token', $feedback->token);
        $this->assertEquals(123, $feedback->timestamp);
    }
}
