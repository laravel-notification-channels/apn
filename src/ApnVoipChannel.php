<?php

namespace NotificationChannels\Apn;

use Illuminate\Notifications\Notification;
use Pushok\Client;

class ApnVoipChannel extends ApnChannel
{
    /**
     * Send the notification to Apple Push Notification Service.
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn_voip', $notification);

        if (empty($tokens)) {
            return null;
        }

        $message = $notification->toApnVoip($notifiable);

        $client = $message->client ?? $this->container->make(Client::class);

        $responses = $this->sendNotifications($client, $message, $tokens);

        $this->dispatchEvents($notifiable, $notification, $responses);

        return $responses;
    }
}
