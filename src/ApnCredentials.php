<?php

namespace NotificationChannels\Apn;

class ApnCredentials
{
    /**
     * The connection environment.
     *
     * @var string
     */
    public $environment;

    /**
     * The connection certificate.
     *
     * @var string
     */
    public $certificate;

    /**
     * The connection pass phrase.
     *
     * @var string
     */
    public $passPhrase;

    /**
     * Create a new instance of the credentials.
     *
     * @param  string  $environment
     * @param  string  $certificate
     * @param  string|null  $passPhrase
     */
    public function __construct($environment, $certificate, $passPhrase = null)
    {
        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }
}
