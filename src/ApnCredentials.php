<?php

namespace NotificationChannels\Apn;


class ApnCredentials
{
    /**
     * The connection environment.
     *
     * @var string
     */
    protected $environment;
    
    /**
     * The connection certificate.
     *
     * @var string
     */
    protected $certificate;
    
    /**
     * The connection pass phrase.
     *
     * @var string
     */
    protected $passPhrase;

    /**
     * Create a new instance of the credentials.
     *
     * @param  string  $environment
     * @param  string  $certificate
     * @param  string|null  $passPhrase
     */
    public function __construct($environment, $certificate, $passPhrase)
    {
        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }

    /**
     * Get the APN environment.
     *
     * @return string
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * Get the APN certificate..
     *
     * @return string
     */
    public function certificate()
    {
        return $this->certificate;
    }

    /**
     * Get the APN pass phrase.
     *
     * @return string
     */
    public function passPhrase()
    {
        return $this->passPhrase;
    }
}
