<?php

namespace NotificationChannels\Apn\Tests;

use Mockery;
use Illuminate\Config\Repository;
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
        $config = new Repository([
            'broadcasting' => [
                'connections' => [
                    'apn' => [
                        'environment' => 'environment',
                        'certificate' => 'certificate',
                        'pass_phrase' => 'pass_phrase',
                    ]
                ]
            ]
        ]);

        return new ApnCredentials($config);
    }
}
