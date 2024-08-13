<?php

namespace NotificationChannels\Apn;

enum ApnMessageInterruptionLevel: string
{
    case Active = 'active';
    case Critical = 'critical';
    case Passive = 'passive';
    case TimeSensitive = 'time-sensitive';
}
