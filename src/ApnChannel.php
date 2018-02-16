<?php

namespace NotificationChannels\Apn;

use Exception;
use Illuminate\Events\Dispatcher;
use ZendService\Apple\Apns\Message\Alert;
use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Message as Packet;
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
    public function __construct(Client $client, Dispatcher $events, ApnCredentials $credentials)
    {
        $this->client = $client;
        $this->events = $events;
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
        $tokens = (array) $notifiable->routeNotificationFor('apn');
        if (empty($tokens)) {
            return;
        }

        $message = $notification->toApn($notifiable);
        if (! $message) {
            return;
        }

        foreach ($tokens as $token) {
            $this->openConnection();
            $packet = $this->getPacket($message, $token);
            $this->sendPacket($notifiable, $notification, $packet, $token);
            $this->closeConnection();
        }
    }

    /**
     * Get the packet for the given message and token.
     *
     * @param  \NotificationChannels\Apn\ApnMessage  $message
     * @param  string
     * @return \ZendService\Apple\Apns\Message
     */
    protected function getPacket($message, $token)
    {
        $alert = new Alert();
        $alert->setTitle($message->title);
        $alert->setBody($message->body);

        $packet = new Packet();
        $packet->setToken($token);
        $packet->setBadge($message->badge);
        $packet->setSound($message->sound);
        $packet->setCategory($message->category);
        $packet->setContentAvailable($message->contentAvailable);
        $packet->setAlert($alert);
        $packet->setCustom($message->custom);

        return $packet;
    }

    /**
     * Sent the notification to the given token.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param \ZendService\Apple\Apns\Message  $packet
     * @param  string  $token
     * @return void
     * @throws \NotificationChannels\Apn\Exceptions\SendingFailed
     */
    protected function sendPacket($notifiable, $notification, $packet, $token)
    {
        try {
            $response = $this->client->send($packet);

            if ($response->getCode() !== Response::RESULT_OK) {
                $this->events->fire(
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
