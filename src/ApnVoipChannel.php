<?php

namespace NotificationChannels\Apn;

use Illuminate\Notifications\Notification;

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

        $client = $message->client ?? $this->factory->instance();

        $responses = $this->sendNotifications($client, $message, $tokens);

        $this->dispatchEvents($notifiable, $notification, $responses);

        return $responses;
    }
}
