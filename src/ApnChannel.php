<?php

namespace Fruitcake\NotificationChannels\Apn;

use Fruitcake\NotificationChannels\Apn\Exceptions\CouldNotSendNotification;
use Fruitcake\NotificationChannels\Apn\Events\MessageWasSent;
use Fruitcake\NotificationChannels\Apn\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class ApnChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws Fruitcake\NotificationChannels\Apn\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
