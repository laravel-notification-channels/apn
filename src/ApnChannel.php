<?php

namespace NotificationChannels\Apn;

use Illuminate\Notifications\Notification;
use Pushok\Client;

class ApnChannel
{
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

        foreach ($tokens as $token) {
            $client->addNotification((new ApnAdapter)->adapt($message, $token));
        }

        return $client->push();
    }
}
