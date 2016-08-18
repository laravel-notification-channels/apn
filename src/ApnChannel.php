<?php

namespace Fruitcake\NotificationChannels\Apn;

use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Message as Packet;
use ZendService\Apple\Apns\Response\Message as Response;

class ApnChannel
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the notification to Apple Push Notification Service
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$this->openConnection()) {
            return;
        }

        $tokens = $notifiable->routeNotificationFor('apn');
        if (!$tokens || count($tokens) == 0) {
            return;
        }
        if (!is_array($tokens)) {
            $tokens = [$tokens];
        }

        $message = $notification->toPushNotification($notifiable);
        if (!$message) {
            return;
        }

        $body = $message->title . ": \n" . $message->message;

        foreach ($tokens as $token) {
            try {
                $packet = new Packet();
                $packet->setToken($token);
                $packet->setAlert($body);
                $packet->setCustom($message->data);

                $response = $this->client->send($packet);

                if($response->getCode() != Response::RESULT_OK) {
                    app()->make('events')->fire(
                        new Events\NotificationFailed($notifiable, $notification, $this, [
                            'token' => $token,
                            'error' => $response->getCode()
                        ])
                    );
                }
            } catch (\Exception $e) {
                // TODO; Should we fire NotificationFailed event here, or throw exception?
                app('log')->error('Error sending APN notification to ' . $notifiable->name . ' (#' . $notifiable->id . ') ' . $e->getMessage());
            }
        }

        $this->closeConnection();
    }

    /**
     * Try to open connection
     *
     * @return bool
     */
    private function openConnection()
    {
        try {
            if (app()->environment() == 'production') {
                $this->client->open(Client::PRODUCTION_URI, storage_path('app/cert/production.pem'));
            } else {
                $this->client->open(Client::SANDBOX_URI, storage_path('app/cert/development.pem'));
            }
            return true;
        } catch (\Exception $e) {
            app('log')->error('Error opening APN connection: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Close connection
     *
     * @return void
     */
    private function closeConnection()
    {
        $this->client->close();
    }
}
