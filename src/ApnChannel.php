<?php

namespace NotificationChannels\Apn;

use Illuminate\Notifications\Notification;
use Pushok\Client;
use Pushok\Notification as PushNotification;

class ApnChannel
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
     * @var \Pushok\Client
     */
    protected $client;

    /**
     * Create a new channel instance.
     *
     * @param  \Pushok\Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the notification to Apple Push Notification Service.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn', $notification);

        if (empty($tokens)) {
            return;
        }

        $message = $notification->toApn($notifiable);

        $payload = (new ApnAdapter)->adapt($message);

        $responses = $this->sendNotifications($tokens, $payload);
        $this->storeResponses($notifiable, $responses);
    }

    /**
     * Returns an array of ApnsResponseInterfaces from the most recent sending of push notifications.
     * @return array
     */
    public function retrieveResponses(){
        return $this->responses;
    }

    /**
     * Send the notification to each of the provided tokens.
     *
     * @param  array  $tokens
     * @param  \Pushok\Payload  $payload
     * @return void
     */
    protected function sendNotifications($tokens, $payload)
    {
        $notifications = [];

        foreach ($tokens as $token) {
            $notifications[] = new PushNotification($payload, $token);
        }

        $this->client->addNotifications($notifications);

        return $this->client->push();
    }

    /**
     * Store the responses from sending the push notification into the notifiable.
     *
     * @param $notifiable
     * @param $responses
     */
    private function storeResponses($notifiable, $responses)
    {
        if (method_exists($notifiable, 'storeResponses'))
        {
            $notifiable->storeResponses($responses);
        }
    }
}
