<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use NotificationChannels\Apn\ApnCredentials;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    protected function getTestCredentials()
    {
        return new ApnCredentials('environment', 'certificate', 'pass_phrase');
    }
}
