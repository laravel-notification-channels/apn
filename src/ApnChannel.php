<?php

namespace NotificationChannels\Apn;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Pushok\Client;
use Pushok\Response;

class ApnChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct(protected ClientFactory $factory, protected Dispatcher $events)
    {
        //
    }

    /**
     * Send the notification to Apple Push Notification Service.
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn', $notification);

        if (empty($tokens)) {
            return null;
        }

        $message = $notification->toApn($notifiable);

        $client = $message->client ?? $this->factory->instance();

        $responses = $this->sendNotifications($client, $message, $tokens);

        $this->dispatchEvents($notifiable, $notification, $responses);

        return $responses;
    }

    /**
     * Send the message to the given tokens through the given client.
     */
    protected function sendNotifications(Client $client, ApnMessage $message, array $tokens): array
    {
        foreach ($tokens as $token) {
            $client->addNotification((new ApnAdapter)->adapt($message, $token));
        }

        return $client->push();
    }

    /**
     * Dispatch failed events for notifications that weren't delivered.
     */
    protected function dispatchEvents(mixed $notifiable, Notification $notification, array $responses): void
    {
        foreach ($responses as $response) {
            if ($response->getStatusCode() === Response::APNS_SUCCESS) {
                continue;
            }

            $event = new NotificationFailed($notifiable, $notification, static::class, [
                'id' => $response->getApnsId(),
                'token' => $response->getDeviceToken(),
                'error' => $response->getErrorReason(),
            ]);

            $this->events->dispatch($event);
        }
    }
}
