<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Config\Repository;

class ApnCredentials
{
    /**
     * The config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new instance of the credentials.
     *
     * @param  \Illuminate\Contracts\Config\Repository
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Get the APN environment.
     *
     * @return string
     */
    public function environment()
    {
        return $this->config->get('broadcasting.connections.apn.environment');
    }

    /**
     * Get the APN certificate..
     *
     * @return string
     */
    public function certificate()
    {
        return $this->config->get('broadcasting.connections.apn.certificate');
    }

    /**
     * Get the APN pass phrase.
     *
     * @return string
     */
    public function passPhrase()
    {
        return $this->config->get('broadcasting.connections.apn.pass_phrase');
    }
}
