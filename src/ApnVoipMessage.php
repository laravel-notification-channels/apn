<?php

namespace NotificationChannels\Apn;

class ApnVoipMessage extends ApnMessage
{
    /**
     * Value indicating when the message will expire.
     *
     * @var \string
     */
    public $pushType = 'voip';
}
