<?php

namespace NotificationChannels\Apn\Tests;

use NotificationChannels\Apn\ApnVoipMessage;

class ApnVoipMessageTest extends TestCase
{
    /** @test */
    public function it_defaults_push_type_to_voip()
    {
        $message = new ApnVoipMessage;

        $this->assertEquals('voip', $message->pushType);
    }
}
