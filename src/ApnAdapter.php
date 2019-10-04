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
        $alert = Alert::create();

        if ($title = $message->title) {
            $alert->setTitle($title);
        }

        if ($body = $message->body) {
            $alert->setBody($body);
        }

        $payload = Payload::create()
            ->setAlert($alert)
            ->setContentAvailability((bool) $message->contentAvailable)
            ->setMutableContent((bool) $message->mutableContent);

        if ($badge = $message->badge) {
            $payload->setBadge($badge);
        }

        if ($sound = $message->sound) {
            $payload->setSound($sound);
        }

        if ($category = $message->category) {
            $payload->setCategory($category);
        }

        foreach ($message->custom as $key => $value) {
            $payload->setCustomValue($key, $value);
        }

        return $payload;
    }
}
