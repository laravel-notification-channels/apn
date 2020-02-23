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
     * The event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new channel instance.
     *
     * @param  \Pushok\Client  $client
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function __construct(Client $client, Dispatcher $events)
    {
        $this->client = $client;
        $this->events = $events;
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

        $responses = $this->sendNotifications(
            $message->client ?? $this->client,
            $message,
            $tokens
        );

        $this->dispatchEvents($notifiable, $notification, $responses);

        return $responses;
    }

    /**
     * Send the message to the given tokens through the given client.
     *
     * @param  \Pushok\Client  $client
     * @param  \NotificationChannels\Apn\ApnMessage  $message
     * @param  array  $tokens
     * @return array
     */
    protected function sendNotifications(Client $client, ApnMessage $message, array $tokens)
    {
        foreach ($tokens as $token) {
            $client->addNotification((new ApnAdapter)->adapt($message, $token));
        }

        return $client->push();
    }

    /**
     * Dispatch failed events for notifications that weren't delivered.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param array $responses
     * @return void
     */
    protected function dispatchEvents($notifiable, $notification, array $responses)
    {
        foreach ($responses as $response) {
            if ($response->getStatusCode() === Response::APNS_SUCCESS) {
                continue;
            }

            $event = new NotificationFailed($notifiable, $notification, $this, [
                'id' => $response->getApnsId(),
                'token' => $response->getDeviceToken(),
                'error' => $response->getErrorReason(),
            ]);

            $this->events->dispatch($event);
        }
    }
}
