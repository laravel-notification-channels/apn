<?php

namespace NotificationChannels\Apn;

use Exception;
use NotificationChannels\Apn\Exception\ConnectionFailed;

trait InteractsWithConnection
{
    /**
     * The connection environment.
     *
     * @var int
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
     * Open the connection to the feedback service.
     *
     * @return void
     * @throws \NotificationChannels\Apn\Exception\ConnectionFailed
     */
    protected function openConnection()
    {
        try {
            $this->client->open($this->environment, $this->certificate, $this->passPhrase);
        } catch (Exception $exception) {
            throw ConnectionFailed::create($exception);
        }
    }

    /**
     * Close the connection.
     *
     * @return void
     */
    protected function closeConnection()
    {
        $this->client->close();
    }
}
