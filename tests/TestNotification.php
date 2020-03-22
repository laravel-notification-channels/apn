<?php

namespace NotificationChannels\Apn\Tests;

class TestNotification extends Notification
{
    public function toApn($notifiable)
    {
        return new ApnMessage('title');
    }

    public function toApnVoip($notifiable)
    {
        return (new ApnMessage)
            ->pushType('voip');
    }
}
