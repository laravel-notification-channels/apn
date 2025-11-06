<?php

namespace NotificationChannels\Apn\Tests;

use DateTime;
use Mockery;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnMessageInterruptionLevel;
use NotificationChannels\Apn\ApnMessagePushType;
use Pushok\Client;

class ApnMessageTest extends TestCase
{
    public function test_it_can_be_created_statically(): void
    {
        $message = ApnMessage::create('title', 'body', ['custom' => 'data'], 1);

        $this->assertInstanceOf(ApnMessage::class, $message);

        $this->assertEquals('title', $message->title);
        $this->assertEquals('body', $message->body);
        $this->assertEquals(['custom' => 'data'], $message->custom);
        $this->assertEquals(1, $message->badge);
    }

    public function test_it_can_set_title(): void
    {
        $message = new ApnMessage;

        $result = $message->title('Title');

        $this->assertEquals('Title', $message->title);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_subtitle(): void
    {
        $message = new ApnMessage;

        $result = $message->subtitle('Subtitle');

        $this->assertEquals('Subtitle', $message->subtitle);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_body(): void
    {
        $message = new ApnMessage;

        $result = $message->body('Body');

        $this->assertEquals('Body', $message->body);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_badge(): void
    {
        $message = new ApnMessage;

        $result = $message->badge(1);

        $this->assertEquals(1, $message->badge);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_sound(): void
    {
        $message = new ApnMessage;

        $result = $message->sound('Laravel Podcast');

        $this->assertEquals('Laravel Podcast', $message->sound);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_interruption_level_as_string(): void
    {
        $message = new ApnMessage;

        $result = $message->interruptionLevel('critical');

        $this->assertEquals('critical', $message->interruptionLevel);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_interruption_level_as_enum(): void
    {
        $message = new ApnMessage;

        $result = $message->interruptionLevel(
            ApnMessageInterruptionLevel::Critical,
        );

        $this->assertEquals('critical', $message->interruptionLevel);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_sound_to_default(): void
    {
        $message = new ApnMessage;

        $result = $message->sound();

        $this->assertEquals('default', $message->sound);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_category(): void
    {
        $message = new ApnMessage;

        $result = $message->category('Category');

        $this->assertEquals('Category', $message->category);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_thread_id(): void
    {
        $message = new ApnMessage;

        $result = $message->threadId('Thread');

        $this->assertEquals('Thread', $message->threadId);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_content_available(): void
    {
        $message = new ApnMessage;

        $result = $message->contentAvailable(1);

        $this->assertEquals(1, $message->contentAvailable);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_push_type(): void
    {
        $message = new ApnMessage;

        $result = $message->pushType(ApnMessagePushType::Background);

        $this->assertEquals(ApnMessagePushType::Background, $message->pushType);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_expires_at(): void
    {
        $message = new ApnMessage;

        $now = new DateTime;

        $result = $message->expiresAt($now);

        $this->assertEquals($now, $message->expiresAt);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_collapse_id(): void
    {
        $message = new ApnMessage;

        $result = $message->collapseId('collapseId');

        $this->assertEquals('collapseId', $message->collapseId);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_apns_id(): void
    {
        $message = new ApnMessage;

        $result = $message->apnsId('123e4567-e89b-12d3-a456-4266554400a0');

        $this->assertEquals(
            '123e4567-e89b-12d3-a456-4266554400a0',
            $message->apnsId,
        );
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_custom_value(): void
    {
        $message = new ApnMessage;

        $result = $message->custom('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $message->custom);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_custom_values(): void
    {
        $message = new ApnMessage;

        $result = $message->custom(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $message->custom);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_url_arg(): void
    {
        $message = new ApnMessage;

        $result = $message->urlArg('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $message->urlArgs);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_url_args(): void
    {
        $message = new ApnMessage;

        $result = $message->urlArgs(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $message->urlArgs);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_custom_alert(): void
    {
        $message = new ApnMessage;

        $result = $message->customAlert('foo');

        $this->assertEquals('foo', $message->customAlert);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_action(): void
    {
        $message = new ApnMessage;

        $result = $message->action('action', ['foo' => 'bar']);

        $expected = [
            'action' => ['action' => 'action', 'params' => ['foo' => 'bar']],
        ];

        $this->assertEquals($expected, $message->custom);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_client(): void
    {
        $message = new ApnMessage;

        $client = Mockery::mock(Client::class);

        $result = $message->via($client);

        $this->assertEquals($client, $message->client);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_mutable_content(): void
    {
        $message = new ApnMessage;

        $result = $message->mutableContent(1);

        $this->assertEquals(1, $message->mutableContent);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_title_loc_key(): void
    {
        $message = new ApnMessage;

        $result = $message->titleLocKey('HELLO_WORLD');

        $this->assertEquals('HELLO_WORLD', $message->titleLocKey);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_title_loc_args(): void
    {
        $message = new ApnMessage;

        $result = $message->titleLocArgs(['hello', 'world']);

        $this->assertEquals(['hello', 'world'], $message->titleLocArgs);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_action_loc_key(): void
    {
        $message = new ApnMessage;

        $result = $message->actionLocKey('hello_world');

        $this->assertEquals('hello_world', $message->actionLocKey);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_loc_key(): void
    {
        $message = new ApnMessage;

        $result = $message->locKey('hello_world');

        $this->assertEquals('hello_world', $message->locKey);
        $this->assertEquals($message, $result);
    }

    public function test_it_can_set_loc_args(): void
    {
        $message = new ApnMessage;

        $result = $message->locArgs(['hello', 'world']);

        $this->assertEquals(['hello', 'world'], $message->locArgs);
        $this->assertEquals($message, $result);
    }
}
