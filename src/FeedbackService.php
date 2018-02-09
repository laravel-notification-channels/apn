<?php

namespace NotificationChannels\Apn;

use ZendService\Apple\Apns\Client\Feedback as Client;

class FeedbackService
{
    use InteractsWithConnection;

    /**
     * The feedback client instance.
     *
     * @var \ZendService\Apple\Apns\Client\Feedback
     */
    protected $client;

    /**
     * Create feedback service instance.
     *
     * @param  \ZendService\Apple\Apns\Client\Feedback  $client
     * @param  \NotificationChannels\Apn\ApnCredentials  $credentials
     */
    public function __construct(Client $client, ApnCredentials $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
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

        $this->closeConnection();

        return $feedback;
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
