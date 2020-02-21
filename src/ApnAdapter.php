<?php

namespace NotificationChannels\Apn;

use Pushok\Notification;
use Pushok\Payload;
use Pushok\Payload\Alert;

class ApnAdapter
{
    /**
     * Convert an ApnMessage instance into a Zend Apns Message.
     *
     * @param  \NotificationChannels\Apn\ApnMessage  $message
     * @param  string  $token
     * @return \Pushok\Notification
     */
    public function adapt(ApnMessage $message, string $token)
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

        $notification = new Notification($payload, $token);

        if ($expiresAt = $message->expiresAt) {
            $notification->setExpirationAt($expiresAt);
        }

        return $notification;
    }
}
