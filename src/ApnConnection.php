<?php

namespace NotificationChannels\Apn;

use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Client\Message as Client;
use NotificationChannels\Apn\Exceptions\ConnectionFailed;

class ApnConnection
{
    /**
     * The sandbox environment identifier.
     *
     * @var int
     */
    const SANDBOX = 0;

    /**
     * The production environment identifier.
     *
     * @var int
     */
    const PRODUCTION = 1;

    /**
     * The APNS client.
     *
     * @var \ZendService\Apple\Apns\Client\Message
     */
    protected $client;

    /**
     * Create a new connection instance.
     *
     * @param  \ZendService\Apple\Apns\Client\Message  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Open a new connection.
     *
     * @param  string  $environment
     * @param  string  $certificate
     * @param  string  $passPhrase
     * @return void
     * @throws \NotificationChannels\Apn\Exceptions\ConnectionFailed
     */
    public function open($environment, $certificate, $passPhrase = null)
    {
        try {
            $this->client->open($environment, $certificate, $passPhrase);
        } catch (Exception $exception) {
            throw ConnectionFailed::create($exception);
        }
    }

    /**
     * Send the provided packet over the existing connection.
     *
     * @param  \ZendService\Apple\Apns\Message  $message
     * @return \ZendService\Apple\Apns\Response\Message
     */
    public function send(Message $packet)
    {
        return $this->client->send($packet);
    }

    /**
     * Close the existing connection.
     *
     * @return void
     */
    public function close()
    {
        $this->client->close();
    }
}
