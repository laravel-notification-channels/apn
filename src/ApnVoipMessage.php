<?php

namespace NotificationChannels\Apn;

class ApnVoipMessage extends ApnMessage
{
    /**
     * Value indicating the push type.
     */
    public ?ApnMessagePushType $pushType = ApnMessagePushType::Voip;
}
