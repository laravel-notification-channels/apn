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
        if($message->customAlert){
            $alert = $message->customAlert;
        } else {
            $alert = Alert::create();

            if ($title = $message->title) {
                $alert->setTitle($title);
            }

            if ($body = $message->body) {
                $alert->setBody($body);
            }

            if ($titleLocArgs = $message->titleLocArgs) {
                $alert->setTitleLocArgs($titleLocArgs);
            }

            if ($titleLocKey = $message->titleLocKey) {
                $alert->setTitleLocKey($titleLocKey);
            }

            if ($actionLocKey = $message->actionLocKey) {
                $alert->setActionLocKey($actionLocKey);
            }

            if ($locArgs = $message->locArgs) {
                $alert->setLocArgs($locArgs);
            }

            if ($locKey = $message->locKey) {
                $alert->setLocKey($locKey);
            }
        }
        

        $payload = Payload::create()
            ->setAlert($alert);

        if ($contentAvailable = $message->contentAvailable) {
            $payload->setContentAvailability((bool) $message->contentAvailable);
        }

        if ($mutableContent = $message->mutableContent) {
            $payload->setMutableContent((bool) $message->mutableContent);
        }

        if (is_int($badge = $message->badge)) {
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

        if ($pushType = $message->pushType) {
            $payload->setPushType($pushType);
        }

        $notification = new Notification($payload, $token);

        if ($expiresAt = $message->expiresAt) {
            $notification->setExpirationAt($expiresAt);
        }

        return $notification;
    }
}
