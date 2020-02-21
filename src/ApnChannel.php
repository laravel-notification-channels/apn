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
     * @return array|void
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn', $notification);

        if (empty($tokens)) {
            return;
        }

        $message = $notification->toApn($notifiable);

        $client = $message->client ?? $this->client;

        $payload = (new ApnAdapter)->adapt($message);

        return $this->sendNotifications($client, $tokens, $payload);
    }

    /**
     * Send the notification to each of the provided tokens.
     *
     * @param  array  $tokens
     * @param  \Pushok\Payload  $payload
     * @return array
     */
    protected function sendNotifications($client, $tokens, $payload)
    {
        $notifications = [];

        foreach ($tokens as $token) {
            $notifications[] = new PushNotification($payload, $token);
        }

        $client->addNotifications($notifications);

        return $client->push();
    }
}
