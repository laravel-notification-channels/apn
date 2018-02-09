<?php

namespace NotificationChannels\Apn\Tests\Exceptions;

use Exception;
use NotificationChannels\Apn\Tests\TestCase;
use NotificationChannels\Apn\Exceptions\ConnectionFailed;

class ConnectionFailedTest extends TestCase
{
    /** @test */
    public function it_can_be_created_statically()
    {
        $baseException = new Exception('Whoops!');

        $exception = ConnectionFailed::create($baseException);

        $this->assertInstanceof(ConnectionFailed::class, $exception);
    }
}
