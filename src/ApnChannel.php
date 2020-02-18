<?php

namespace NotificationChannels\Apn;

use Pushok\Client;
use Illuminate\Notifications\Notification;
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

        $this->sendNotifications($tokens, $payload);
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

        $this->client->push();
    }
}
