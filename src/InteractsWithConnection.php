<?php

namespace NotificationChannels\Apn;

use Exception;
use NotificationChannels\Apn\Exception\ConnectionFailed;

trait InteractsWithConnection
{
    /**
     * The connection credentials.
     *
     * @var \NotificationChannels\Apn\ApnCredentials
     */
    protected $credentials;

    /**
     * Open the connection to the feedback service.
     *
     * @return void
     * @throws \NotificationChannels\Apn\Exception\ConnectionFailed
     */
    protected function openConnection()
    {
        try {
            $this->client->open(
                $this->credentials->environment(),
                $this->credentials->certificate(),
                $this->credentials->passPhrase()
            );
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
