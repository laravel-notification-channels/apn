<?php

namespace NotificationChannels\Apn\Tests;

use DateTime;
use NotificationChannels\Apn\ApnAdapter;
use NotificationChannels\Apn\ApnMessage;

class ApnAdapterTest extends TestCase
{
    protected $adapter;

    public function setUp(): void
    {
        $this->adapter = new ApnAdapter;
    }

    public function test_it_adapts_title()
    {
        $message = (new ApnMessage)->title('title');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('title', $notification->getPayload()->getAlert()->getTitle());
    }

    public function test_it_adapts_subtitle()
    {
        $message = (new ApnMessage)->subtitle('subtitle');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('subtitle', $notification->getPayload()->getAlert()->getSubtitle());
    }

    public function test_it_adapts_body()
    {
        $message = (new ApnMessage)->body('body');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('body', $notification->getPayload()->getAlert()->getBody());
    }

    public function test_it_adapts_content_available()
    {
        $message = (new ApnMessage)->contentAvailable(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->isContentAvailable());
    }

    public function test_it_does_not_set_content_available_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->isContentAvailable());
    }

    public function test_it_adapts_mutable_content()
    {
        $message = (new ApnMessage)->mutableContent(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->hasMutableContent());
    }

    public function test_it_does_not_set_mutable_content_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->hasMutableContent());
    }

    public function test_it_adapts_content_state()
    {
        $contentState = ['status' => 'active', 'count' => 5];
        $message = (new ApnMessage)->contentState($contentState);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals($contentState, $notification->getPayload()->getContentState());
    }

    public function test_it_does_not_set_content_state_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getContentState());
    }

    public function test_it_adapts_event()
    {
        $message = (new ApnMessage)->event('update');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('update', $notification->getPayload()->getEvent());
    }

    public function test_it_does_not_set_event_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getEvent());
    }

    public function test_it_adapts_timestamp()
    {
        $message = (new ApnMessage)->timestamp(1234567890);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(1234567890, $notification->getPayload()->getTimestamp());
    }

    public function test_it_does_not_set_timestamp_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getTimestamp());
    }

    public function test_it_adapts_attributes_type()
    {
        $message = (new ApnMessage)->attributesType('dateTime');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('dateTime', $notification->getPayload()->getAttributesType());
    }

    public function test_it_does_not_set_attributes_type_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAttributesType());
    }

    public function test_it_adapts_attributes()
    {
        $attributes = ['status' => 'active', 'count' => 5];
        $message = (new ApnMessage)->setAttributes($attributes);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals($attributes, $notification->getPayload()->getAttributes());
    }

    public function test_it_does_not_set_attributes_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals([], $notification->getPayload()->getAttributes());
    }

    public function test_it_adapts_badge()
    {
        $message = (new ApnMessage)->badge(1);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(1, $notification->getPayload()->getBadge());
    }

    public function test_it_adapts_badge_clear()
    {
        $message = (new ApnMessage)->badge(0);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertSame(0, $notification->getPayload()->getBadge());
    }

    public function test_it_adapts_sound()
    {
        $message = (new ApnMessage)->sound('sound');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('sound', $notification->getPayload()->getSound());
    }

    public function test_it_adapts_interruption_level()
    {
        $message = (new ApnMessage)->interruptionLevel('interruption-level');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('interruption-level', $notification->getPayload()->getInterruptionLevel());
    }

    public function test_it_adapts_category()
    {
        $message = (new ApnMessage)->category('category');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('category', $notification->getPayload()->getCategory());
    }

    public function test_it_adapts_thread_id()
    {
        $message = (new ApnMessage)->threadId('threadId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('threadId', $notification->getPayload()->getThreadId());
    }

    public function test_it_adapts_custom()
    {
        $message = (new ApnMessage)->custom('key', 'value');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('value', $notification->getPayload()->getCustomValue('key'));
    }

    public function test_it_adapts_custom_alert()
    {
        $message = (new ApnMessage)->setCustomAlert('custom');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('custom', $notification->getPayload()->getAlert());
    }

    public function test_it_adapts_push_type()
    {
        $message = (new ApnMessage)->pushType('push type');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('push type', $notification->getPayload()->getPushType());
    }

    public function test_it_adapts_expires_at()
    {
        $expiresAt = new DateTime('2000-01-01');

        $message = (new ApnMessage)->expiresAt($expiresAt);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals($expiresAt, $notification->getExpirationAt());
    }

    public function test_it_adapts_collapse_id()
    {
        $message = (new ApnMessage)->collapseId('collapseId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('collapseId', $notification->getCollapseId());
    }

    public function test_it_adapts_apns_id()
    {
        $message = (new ApnMessage)->apnsId('123e4567-e89b-12d3-a456-4266554400a0');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('123e4567-e89b-12d3-a456-4266554400a0', $notification->getId());
    }

    public function test_it_adapts_background_without_alert(): void
    {
        $message = (new ApnMessage)->pushType('background');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAlert());
    }

    public function test_it_adapts_live_activity_without_alert(): void
    {
        $message = (new ApnMessage)->pushType('liveactivity');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAlert());
    }
}
