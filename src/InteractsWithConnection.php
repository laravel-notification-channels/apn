<?php

namespace NotificationChannels\Apn;

use Exception;
use NotificationChannels\Apn\Exceptions\ConnectionFailed;

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
     * @param \NotificationChannels\Apn\ApnCredentials $credentials
     *
     * @return void
     * @throws \NotificationChannels\Apn\Exception\ConnectionFailed
     */
    protected function openConnection(ApnCredentials $credentials = null)
    {
        try {
            $credentials = $credentials ?: $this->credentials;

            $this->client->open($credentials->environment, $credentials->certificate, $credentials->passPhrase);
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
