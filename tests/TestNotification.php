<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Notifications\Notification;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Apn\ApnVoipMessage;

class TestNotification extends Notification
{
    public function toApn($notifiable)
    {
        return new ApnMessage('title');
    }

    public function toApnVoip($notifiable)
    {
        return new ApnVoipMessage('title');
    }
}
