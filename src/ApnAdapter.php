<?php

namespace NotificationChannels\Apn;

use Pushok\Payload;
use Pushok\Payload\Alert;

class ApnAdapter
{
    /**
     * Convert an ApnMessage instance into a Zend Apns Message.
     *
     * @param  \NotificationChannels\Apn\ApnMessage  $message
     * @return \Pushok\Payload
     */
    public function adapt(ApnMessage $message)
    {
        $alert = Alert::create()
            ->setTitle($message->title)
            ->setBody($message->body);

        $payload = Payload::create()
            ->setAlert($alert)
            ->setBadge($message->badge)
            ->setSound($message->sound)
            ->setCategory($message->setCategory)
            ->setContentAvailability($message->contentAvailable)
            ->setMutableContent($message->mutableContent);

        foreach ($message->custom as $key => $value) {
            $payload->setCustomValue($key, $value);
        }
    }
}
