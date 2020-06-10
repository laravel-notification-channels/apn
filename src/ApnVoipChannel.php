<?php

namespace NotificationChannels\Apn;

use Illuminate\Notifications\Notification;

class ApnVoipChannel extends ApnChannel
{
    /**
     * Send the notification to Apple Push Notification Service.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return array|void
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn_voip', $notification);

        if (empty($tokens)) {
            return;
        }

        $message = $notification->toApnVoip($notifiable);

        $client = $message->client ?? $this->factory->instance();

        $responses = $this->sendNotifications($client, $message, $tokens);

        $this->dispatchEvents($notifiable, $notification, $responses);

        return $responses;
    }
}
