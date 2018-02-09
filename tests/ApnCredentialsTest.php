<?php

namespace NotificationChannels\Apn\Tests;

class ApnCredentialsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->credentials = $this->getTestCredentials();
    }

    /** @test */
    function it_gets_environment()
    {
        $this->assertEquals('environment', $this->credentials->environment());
    }

    /** @test */
    function it_gets_certificate()
    {
        $this->assertEquals('certificate', $this->credentials->certificate());
    }

    /** @test */
    function it_gets_pass_phrase()
    {
        $this->assertEquals('pass_phrase', $this->credentials->passPhrase());
    }
}
