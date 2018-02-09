<?php

namespace NotificationChannels\Apn;

use ZendService\Apple\Apns\Client\Feedback as Client;

class FeedbackService
{
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
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get feedback from the Apple Feedback Service about failed deliveries.
     *
     * @return array|ApnFeedback[]
     * @throws Exceptions\ConnectionFailed
     */
    public function get()
    {
        $feedback = [];

        /** @var FeedbackResponse $response */
        foreach ($this->client->feedback() as $response) {
            $feedback[] = new ApnFeedback($response->getToken(), $response->getTime());
        }

        return $feedback;
    }
}
