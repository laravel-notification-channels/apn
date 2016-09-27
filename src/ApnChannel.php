<?php

namespace NotificationChannels\Apn;

use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\Exceptions\SendingFailed;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Message as Packet;
use ZendService\Apple\Apns\Message\Alert;
use ZendService\Apple\Apns\Response\Message as Response;

class ApnChannel
{
    const SANDBOX = 0;
    const PRODUCTION = 1;

    /** @var string */
    protected $environment;

    /** @var string */
    protected $certificate;

    /** @var string|null */
    protected $passPhrase;

    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /**
     * @param \ZendService\Apple\Apns\Client\Message $client
     * @param \Illuminate\Events\Dispatcher $events
     * @param string $environment
     * @param string $certificate
     * @param string|null $passPhrase
     */
    public function __construct(Client $client, Dispatcher $events, $environment, $certificate, $passPhrase = null)
    {
        $this->client = $client;
        $this->events = $events;
        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passphrase = $passPhrase;
    }

    /**
     * Send the notification to Apple Push Notification Service.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Apn\Exceptions\SendingFailed
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn');
        if (! $tokens) {
            return;
        }

        $message = $notification->toApn($notifiable);
        if (! $message) {
            return;
        }
        
        if (! $this->openConnection()) {
            return;
        }

        foreach ($tokens as $token) {
            try {
                $alert = new Alert();
                $alert->setTitle($message->title);
                $alert->setBody($message->body);

                $packet = new Packet();
                $packet->setToken($token);
                $packet->setBadge($message->badge);
                $packet->setSound($message->sound);
                $packet->setAlert($alert);
                $packet->setCustom($message->custom);

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

        $this->closeConnection();
    }

    /**
     * Open the connection.
     *
     * @return bool
     *
     * @throws \NotificationChannels\Apn\Exceptions\ConnectionFailed
     */
    private function openConnection()
    {
        try {
            $this->client->open($this->environment, $this->certificate, $this->passPhrase);

            return true;
        } catch (Exception $exception) {
            throw Exceptions\ConnectionFailed::create($exception);
        }
    }

    /**
     * Close the connection.
     */
    private function closeConnection()
    {
        $this->client->close();
    }
}
