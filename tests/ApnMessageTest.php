<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use NotificationChannels\Apn\ApnMessage;
use Pushok\Client;

class ApnMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_created_statically()
    {
        $message = ApnMessage::create('title', 'body', ['custom' => 'data'], 1);

        $this->assertInstanceOf(ApnMessage::class, $message);

        $this->assertEquals('title', $message->title);
        $this->assertEquals('body', $message->body);
        $this->assertEquals(['custom' => 'data'], $message->custom);
        $this->assertEquals(1, $message->badge);
    }

    /** @test */
    public function it_can_set_title()
    {
        $message = new ApnMessage;

        $message->title('Title');

        $this->assertEquals('Title', $message->title);
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = new ApnMessage;

        $message->body('Body');

        $this->assertEquals('Body', $message->body);
    }

    /** @test */
    public function it_can_set_badge()
    {
        $message = new ApnMessage;

        $message->badge(1);

        $this->assertEquals(1, $message->badge);
    }

    /** @test */
    public function it_can_set_sound()
    {
        $message = new ApnMessage;

        $message->sound('Laravel Podcast');

        $this->assertEquals('Laravel Podcast', $message->sound);
    }

    /** @test */
    public function it_can_set_sound_to_default()
    {
        $message = new ApnMessage;

        $message->sound();

        $this->assertEquals('default', $message->sound);
    }

    /** @test */
    public function it_can_set_category()
    {
        $message = new ApnMessage;

        $message->category('Category');

        $this->assertEquals('Category', $message->category);
    }

    /** @test */
    public function it_can_set_content_available()
    {
        $message = new ApnMessage;

        $message->contentAvailable(1);

        $this->assertEquals(1, $message->contentAvailable);
    }

    /** @test */
    public function it_can_set_push_type()
    {
        $message = new ApnMessage;

        $message->pushType('type');

        $this->assertEquals('type', $message->pushType);
    }

    /** @test */
    public function it_can_set_custom_value()
    {
        $message = new ApnMessage;

        $message->custom('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $message->custom);
    }

    /** @test */
    public function it_can_set_custom_values()
    {
        $message = new ApnMessage;

        $message->setCustom(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $message->custom);
    }

    /** @test */
    public function it_can_set_action()
    {
        $message = new ApnMessage;

        $message->action('action', ['foo' => 'bar']);

        $expected = ['action' => ['action' => 'action', 'params' => ['foo' => 'bar']]];

        $this->assertEquals($expected, $message->custom);
    }

    /** @test */
    public function it_can_set_client()
    {
        $message = new ApnMessage;

        $client = Mockery::mock(Client::class);

        $message->via($client);

        $this->assertEquals($client, $message->client);
    }

    /** @test */
    public function it_can_set_mutable_content()
    {
        $message = new ApnMessage;

        $message->mutableContent(1);

        $this->assertEquals(1, $message->mutableContent);
    }
}
