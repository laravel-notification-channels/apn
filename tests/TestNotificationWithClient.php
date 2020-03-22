<?php

namespace NotificationChannels\Apn\Tests;

class TestNotificationWithClient extends Notification
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function toApn($notifiable)
    {
        return (new ApnMessage('title'))->via($this->client);
    }

    public function toApnVoip($notifiable)
    {
        return (new ApnMessage)->pushType('voip')->via($this->client);
    }
}
