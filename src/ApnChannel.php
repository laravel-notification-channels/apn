<?php

namespace NotificationChannels\Apn;

use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Client\Message as Client;
use NotificationChannels\Apn\Exceptions\SendingFailed;
use Illuminate\Notifications\Events\NotificationFailed;
use ZendService\Apple\Apns\Response\Message as Response;

class ApnChannel
{
    use InteractsWithConnection;

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

    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /** @var \NotificationChannels\Apn\ApnCredentials */
    protected $credentials;

    /**
     * Create a new instance of the APN channel.
     *
     * @param  \ZendService\Apple\Apns\Client\Message  $client
     * @param  \Illuminate\Events\Dispatcher  $events
     * @param  \NotificationChannels\Apn\ApnCredentials  $credentials
     */
    public function __construct(
        Client $client,
        Dispatcher $events,
        ApnAdapter $adapter,
        ApnCredentials $credentials
    ) {
        $this->client = $client;
        $this->events = $events;
        $this->adapter = $adapter;
        $this->credentials = $credentials;
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
        if (! $message) {
            return;
        }

        $this->sendNotifications($notifiable, $notification, $tokens, $message);
    }

    /**
     * Send the notification to each of the provided tokens.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     */
    protected function sendNotifications($notifiable, $notification, $tokens, $message)
    {
        foreach ($tokens as $token) {
            $this->openConnection($message->credentials);

            $this->sendNotification(
                $notifiable,
                $notification,
                $this->adapter->adapt($message, $token),
                $token
            );

            $this->closeConnection();
        }
    }

    /**
     * Sent the notification to the given token.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param \ZendService\Apple\Apns\Message  $message
     * @param  string  $token
     * @return void
     * @throws \NotificationChannels\Apn\Exceptions\SendingFailed
     */
    protected function sendNotification($notifiable, $notification, $message, $token)
    {
        try {
            $response = $this->client->send($message);

            if ($response->getCode() !== Response::RESULT_OK) {
                $this->events->dispatch(
                    new NotificationFailed($notifiable, $notification, $this, [
                        'token' => $token,
                        'error' => $response->getCode(),
                    ])
                );
            }
        } catch (Exception $e) {
            throw SendingFailed::create($e);
        }
    }
}
