<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnVoipMessage;

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
        return (new ApnVoipMessage)->via($this->client);
    }
}
