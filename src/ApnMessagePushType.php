<?php

namespace NotificationChannels\Apn;

enum ApnMessagePushType: string
{
    case Alert = 'alert';
    case Background = 'background';
    case Voip = 'voip';
    case Complication = 'complication';
    case FileProvider = 'fileprovider';
    case Mdm = 'mdm';
    case LiveActivity = 'liveactivity';
}
