<?php

namespace NotificationChannels\Apn\Tests;

use NotificationChannels\Apn\ApnMessagePushType;
use NotificationChannels\Apn\ApnVoipMessage;

class ApnVoipMessageTest extends TestCase
{
    public function test_it_defaults_push_type_to_voip()
    {
        $message = new ApnVoipMessage;

        $this->assertEquals(ApnMessagePushType::Voip, $message->pushType);
    }
}
