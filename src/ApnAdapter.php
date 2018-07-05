<?php

namespace NotificationChannels\Apn;

use ZendService\Apple\Apns\Message;
use ZendService\Apple\Apns\Message\Alert;

class ApnAdapter
{
    /**
     * Convert an ApnMessage instance into a Zend Apns Message.
     *
     * @param  \NotificationChannels\Apn\ApnMessage  $message
     * @param  string  $token
     * @return \ZendService\Apple\Apns\Message
     */
    public function adapt(ApnMessage $message, $token)
    {
        $alert = new Alert();
        $alert->setTitle($message->title);
        $alert->setBody($message->body);

        $packet = new Message;
        $packet->setToken($token);
        $packet->setBadge($message->badge);
        $packet->setSound($message->sound);
        $packet->setCategory($message->category);
        $packet->setContentAvailable($message->contentAvailable);
        $packet->setAlert($alert);
        $packet->setCustom($message->custom);
        $packet->setUrlArgs($message->urlArguments);

        return $packet;
    }
}
