<?php

namespace NotificationChannels\Apn;

class ApnVoipMessage extends ApnMessage
{
    /**
     * Value indicating when the message will expire.
     */
    public ?string $pushType = 'voip';
}
