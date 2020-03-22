<?php

namespace NotificationChannels\Apn\Tests;

use DateTime;
use Mockery;
use NotificationChannels\Apn\ApnVoipMessage;
use Pushok\Client;

class ApnVoipMessageTest extends TestCase
{
    /** @test */
    public function it_defaults_push_type_to_voip()
    {
        $message = new ApnVoipMessage;

        $this->assertEquals('voip', $message->pushType);
    }
}
