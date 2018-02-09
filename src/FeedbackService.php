<?php

namespace NotificationChannels\Apn;

use Exception;
use ZendService\Apple\Apns\Client\Feedback as Client;
use NotificationChannels\Apn\Exception\ConnectionFailed;

class FeedbackService
{
    /**
     * The feedback client instance.
     *
     * @var \ZendService\Apple\Apns\Client\Feedback
     */
    protected $client;

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
     * Create feedback service instance.
     *
     * @param  \ZendService\Apple\Apns\Client\Feedback  $client
     * @param  int  $environment
     * @param  string  $certificate
     * @param  string|null  $passPhrase
     */
    public function __construct(Client $client, $environment, $certificate, $passPhrase = null)
    {
        $this->client = $client;
        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }

    /**
     * Get feedback from the Apple Feedback Service about failed deliveries.
     *
     * @return array|ApnFeedback[]
     * @throws Exceptions\ConnectionFailed
     */
    public function get()
    {
        $this->openConnection();

        $feedback = $this->fetchFeedback();

        $this->client->close();

        return $feedback;
    }

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
     * Fetch the feedback from APNS and collect our feedback object.
     *
     * @return array
     */
    protected function fetchFeedback()
    {
        $feedback = [];

        /** @var FeedbackResponse $response */
        foreach ($this->client->feedback() as $response) {
            $feedback[] = new ApnFeedback($response->getToken(), $response->getTime());
        }

        return $feedback;
    }
}
