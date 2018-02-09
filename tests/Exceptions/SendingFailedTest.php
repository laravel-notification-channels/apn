<?php

namespace NotificationChannels\Apn\Tests\Exceptions;

use Exception;
use NotificationChannels\Apn\Tests\TestCase;
use NotificationChannels\Apn\Exceptions\SendingFailed;

class SendingFailedTest extends TestCase
{
    /** @test */
    public function it_can_be_created_statically()
    {
        $baseException = new Exception('Whoops!');

        $exception = SendingFailed::create($baseException);

        $this->assertInstanceof(SendingFailed::class, $exception);
    }
}
