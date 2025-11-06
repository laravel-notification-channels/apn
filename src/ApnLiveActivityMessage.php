<?php

namespace NotificationChannels\Apn;

class ApnLiveActivityMessage extends ApnMessage
{
    /**
     * Value indicating the push type.
     */
    public ?ApnMessagePushType $pushType = ApnMessagePushType::LiveActivity;
}
