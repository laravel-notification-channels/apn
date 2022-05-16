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

    /** @test */
    public function it_adapts_title()
    {
        $message = (new ApnMessage)->title('title');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('title', $notification->getPayload()->getAlert()->getTitle());
    }

    /** @test */
    public function it_adapts_subtitle()
    {
        $message = (new ApnMessage)->subtitle('subtitle');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('subtitle', $notification->getPayload()->getAlert()->getSubtitle());
    }

    /** @test */
    public function it_adapts_body()
    {
        $message = (new ApnMessage)->body('body');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('body', $notification->getPayload()->getAlert()->getBody());
    }

    /** @test */
    public function it_adapts_content_available()
    {
        $message = (new ApnMessage)->contentAvailable(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->isContentAvailable());
    }

    /** @test */
    public function it_does_not_set_content_available_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->isContentAvailable());
    }

    /** @test */
    public function it_adapts_mutable_content()
    {
        $message = (new ApnMessage)->mutableContent(true);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertTrue($notification->getPayload()->hasMutableContent());
    }

    /** @test */
    public function it_does_not_set_mutable_content_by_default()
    {
        $message = (new ApnMessage);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->hasMutableContent());
    }

    /** @test */
    public function it_adapts_badge()
    {
        $message = (new ApnMessage)->badge(1);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals(1, $notification->getPayload()->getBadge());
    }

    /** @test */
    public function it_adapts_badge_clear()
    {
        $message = (new ApnMessage)->badge(0);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertSame(0, $notification->getPayload()->getBadge());
    }

    /** @test */
    public function it_adapts_sound()
    {
        $message = (new ApnMessage)->sound('sound');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('sound', $notification->getPayload()->getSound());
    }

    /** @test */
    public function it_adapts_interruption_level()
    {
        $message = (new ApnMessage)->interruptionLevel('interruption-level');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('interruption-level', $notification->getPayload()->getInterruptionLevel());
    }

    /** @test */
    public function it_adapts_category()
    {
        $message = (new ApnMessage)->category('category');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('category', $notification->getPayload()->getCategory());
    }

    /** @test */
    public function it_adapts_thread_id()
    {
        $message = (new ApnMessage)->threadId('threadId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('threadId', $notification->getPayload()->getThreadId());
    }

    /** @test */
    public function it_adapts_custom()
    {
        $message = (new ApnMessage)->custom('key', 'value');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('value', $notification->getPayload()->getCustomValue('key'));
    }

    /** @test */
    public function it_adapts_custom_alert()
    {
        $message = (new ApnMessage)->setCustomAlert('custom');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('custom', $notification->getPayload()->getAlert());
    }

    /** @test */
    public function it_adapts_push_type()
    {
        $message = (new ApnMessage)->pushType('push type');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('push type', $notification->getPayload()->getPushType());
    }

    /** @test */
    public function it_adapts_expires_at()
    {
        $expiresAt = new DateTime('2000-01-01');

        $message = (new ApnMessage)->expiresAt($expiresAt);

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals($expiresAt, $notification->getExpirationAt());
    }

    /** @test */
    public function it_adapts_collapse_id()
    {
        $message = (new ApnMessage)->collapseId('collapseId');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertEquals('collapseId', $notification->getCollapseId());
    }

    /** @test */
    public function it_adapts_background_without_alert(): void
    {
        $message = (new ApnMessage)->pushType('background');

        $notification = $this->adapter->adapt($message, 'token');

        $this->assertNull($notification->getPayload()->getAlert());
    }
}
