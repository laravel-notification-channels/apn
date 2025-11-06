<?php

namespace NotificationChannels\Apn\Tests;

use NotificationChannels\Apn\ApnLiveActivityMessage;
use NotificationChannels\Apn\ApnMessagePushType;

class ApnLiveActivityMessageTest extends TestCase
{
    public function test_it_defaults_push_type_to_live_activity(): void
    {
        $message = new ApnLiveActivityMessage;

        $this->assertEquals(
            ApnMessagePushType::LiveActivity,
            $message->pushType,
        );
    }
}
