<?php

namespace NotificationChannels\Apn\Tests;

use DateTime;
use NotificationChannels\Apn\ApnAdapter;
use NotificationChannels\Apn\ApnLiveActivityMessage;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnMessagePushType;

class ApnAdapterTest extends TestCase
{
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = new ApnAdapter;
    }

    public function test_it_adapts_title(): void
    {
        $message = new ApnMessage()->title('title');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'title',
            $notification->getPayload()->getAlert()->getTitle(),
        );
    }

    public function test_it_adapts_subtitle(): void
    {
        $message = new ApnMessage()->subtitle('subtitle');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'subtitle',
            $notification->getPayload()->getAlert()->getSubtitle(),
        );
    }

    public function test_it_adapts_body(): void
    {
        $message = new ApnMessage()->body('body');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'body',
            $notification->getPayload()->getAlert()->getBody(),
        );
    }

    public function test_it_adapts_content_available(): void
    {
        $message = new ApnMessage()->contentAvailable(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->isContentAvailable());
    }

    public function test_it_does_not_set_content_available_by_default(): void
    {
        $message = new ApnMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->isContentAvailable());
    }

    public function test_it_adapts_mutable_content(): void
    {
        $message = new ApnMessage()->mutableContent(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->hasMutableContent());
    }

    public function test_it_does_not_set_mutable_content_by_default(): void
    {
        $message = new ApnMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->hasMutableContent());
    }

    public function test_it_adapts_content_state(): void
    {
        $contentState = ['status' => 'active', 'count' => 5];
        $message = new ApnLiveActivityMessage()->contentState($contentState);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            $contentState,
            $notification->getPayload()->getContentState(),
        );
    }

    public function test_it_does_not_set_content_state_by_default(): void
    {
        $message = new ApnLiveActivityMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getContentState());
    }

    public function test_it_adapts_event(): void
    {
        $message = new ApnLiveActivityMessage()->event('update');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('update', $notification->getPayload()->getEvent());
    }

    public function test_it_does_not_set_event_by_default(): void
    {
        $message = new ApnLiveActivityMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getEvent());
    }

    public function test_it_adapts_timestamp(): void
    {
        $message = new ApnLiveActivityMessage()->timestamp(1234567890);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            1234567890,
            $notification->getPayload()->getTimestamp(),
        );
    }

    public function test_it_does_not_set_timestamp_by_default(): void
    {
        $message = new ApnLiveActivityMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getTimestamp());
    }

    public function test_it_adapts_attributes_type(): void
    {
        $message = new ApnLiveActivityMessage()->attributesType('dateTime');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'dateTime',
            $notification->getPayload()->getAttributesType(),
        );
    }

    public function test_it_does_not_set_attributes_type_by_default(): void
    {
        $message = new ApnMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAttributesType());
    }

    public function test_it_adapts_attributes(): void
    {
        $attributes = ['status' => 'active', 'count' => 5];
        $message = new ApnLiveActivityMessage()->attributes($attributes);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            $attributes,
            $notification->getPayload()->getAttributes(),
        );
    }

    public function test_it_does_not_set_attributes_by_default(): void
    {
        $message = new ApnLiveActivityMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals([], $notification->getPayload()->getAttributes());
    }

    public function test_it_adapts_dismissal_date(): void
    {
        $message = new ApnLiveActivityMessage()->dismissalDate(1700000000);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            1700000000,
            $notification->getPayload()->getDismissalDate(),
        );
    }

    public function test_it_does_not_set_dismissal_date_by_default(): void
    {
        $message = new ApnLiveActivityMessage;

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getDismissalDate());
    }

    public function test_it_adapts_badge(): void
    {
        $message = new ApnMessage()->badge(1);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(1, $notification->getPayload()->getBadge());
    }

    public function test_it_adapts_badge_clear(): void
    {
        $message = new ApnMessage()->badge(0);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertSame(0, $notification->getPayload()->getBadge());
    }

    public function test_it_adapts_sound(): void
    {
        $message = new ApnMessage()->sound('sound');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('sound', $notification->getPayload()->getSound());
    }

    public function test_it_adapts_interruption_level(): void
    {
        $message = new ApnMessage()->interruptionLevel('interruption-level');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'interruption-level',
            $notification->getPayload()->getInterruptionLevel(),
        );
    }

    public function test_it_adapts_category(): void
    {
        $message = new ApnMessage()->category('category');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'category',
            $notification->getPayload()->getCategory(),
        );
    }

    public function test_it_adapts_thread_id(): void
    {
        $message = new ApnMessage()->threadId('threadId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'threadId',
            $notification->getPayload()->getThreadId(),
        );
    }

    public function test_it_adapts_custom(): void
    {
        $message = new ApnMessage()->custom('key', 'value');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            'value',
            $notification->getPayload()->getCustomValue('key'),
        );
    }

    public function test_it_adapts_custom_alert(): void
    {
        $message = new ApnMessage()->customAlert('custom');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('custom', $notification->getPayload()->getAlert());
    }

    public function test_it_adapts_push_type()
    {
        $message = new ApnMessage()->pushType(ApnMessagePushType::Background);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            ApnMessagePushType::Background,
            $notification->getPayload()->getPushType(),
        );
    }

    public function test_it_adapts_expires_at(): void
    {
        $expiresAt = new DateTime('2000-01-01');

        $message = new ApnMessage()->expiresAt($expiresAt);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals($expiresAt, $notification->getExpirationAt());
    }

    public function test_it_adapts_collapse_id(): void
    {
        $message = new ApnMessage()->collapseId('collapseId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('collapseId', $notification->getCollapseId());
    }

    public function test_it_adapts_apns_id(): void
    {
        $message = new ApnMessage()->apnsId(
            '123e4567-e89b-12d3-a456-4266554400a0',
        );

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(
            '123e4567-e89b-12d3-a456-4266554400a0',
            $notification->getId(),
        );
    }

    public function test_it_adapts_background_without_alert(): void
    {
        $message = new ApnMessage()->pushType(ApnMessagePushType::Background);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAlert());
    }

    public function test_it_adapts_live_activity_without_alert(): void
    {
        $message = new ApnMessage()->pushType(ApnMessagePushType::LiveActivity);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAlert());
    }
}
