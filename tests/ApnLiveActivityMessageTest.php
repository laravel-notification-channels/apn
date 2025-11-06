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

    public function test_it_can_set_content_state(): void
    {
        $message = new ApnLiveActivityMessage;

        $contentState = ['status' => 'active', 'count' => 5];

        $result = $message->contentState($contentState);

        $this->assertEquals($contentState, $message->contentState);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_content_state_to_null(): void
    {
        $message = new ApnLiveActivityMessage;

        $contentState = ['status' => 'active', 'count' => 5];
        $message->contentState($contentState);

        $result = $message->contentState(null);

        $this->assertEquals(null, $message->contentState);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_event(): void
    {
        $message = new ApnLiveActivityMessage;

        $result = $message->event('update');

        $this->assertEquals('update', $message->event);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_event_to_null(): void
    {
        $message = new ApnLiveActivityMessage;

        $message->event('update');

        $result = $message->event(null);

        $this->assertEquals(null, $message->event);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_timestamp(): void
    {
        $message = new ApnLiveActivityMessage;

        $result = $message->timestamp(1234567890);

        $this->assertEquals(1234567890, $message->timestamp);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_timestamp_to_null(): void
    {
        $message = new ApnLiveActivityMessage;

        $message->timestamp(1234567890);

        $result = $message->timestamp(null);

        $this->assertEquals(null, $message->timestamp);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_attributes_type(): void
    {
        $message = new ApnLiveActivityMessage;

        $result = $message->attributesType('dateTime');

        $this->assertEquals('dateTime', $message->attributesType);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_attributes_type_to_null(): void
    {
        $message = new ApnLiveActivityMessage;

        $message->attributesType('dateTime');

        $result = $message->attributesType(null);

        $this->assertEquals(null, $message->attributesType);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_add_attribute(): void
    {
        $message = new ApnLiveActivityMessage;

        $result = $message->attribute('key', 'value');

        $this->assertEquals(['key' => 'value'], $message->attributes);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_add_multiple_attributes(): void
    {
        $message = new ApnLiveActivityMessage;

        $message->attribute('key1', 'value1')->attribute('key2', 'value2');

        $this->assertEquals(
            ['key1' => 'value1', 'key2' => 'value2'],
            $message->attributes,
        );
    }

    public function test_it_can_set_attributes(): void
    {
        $message = new ApnLiveActivityMessage;

        $attributes = ['status' => 'active', 'count' => 5];

        $result = $message->attributes($attributes);

        $this->assertEquals($attributes, $message->attributes);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_dismissal_date(): void
    {
        $message = new ApnLiveActivityMessage;

        $result = $message->dismissalDate(1700000000);

        $this->assertEquals(1700000000, $message->dismissalDate);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_dismissal_date_to_null(): void
    {
        $message = new ApnLiveActivityMessage;

        $message->dismissalDate(1700000000);

        $result = $message->dismissalDate(null);

        $this->assertEquals(null, $message->dismissalDate);
        $this->assertEquals($message, $result);
    }
}
