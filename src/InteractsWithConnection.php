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
     * @return void
     * @throws \NotificationChannels\Apn\Exception\ConnectionFailed
     */
    protected function openConnection($environment = null, $certificate = '',$passphrase = '')
    {
        if($environment === null) {
            $environment = $this->credentials->environment();
        }
        if(empty($certificate)) {
            $certificate = $this->credentials->certificate();
        }
        if(empty($passphrase)) {
            $passphrase = $this->credentials->passPhrase();
        }
        
        try {
            $this->client->open(
                $environment,
                $certificate,
                $passphrase
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
