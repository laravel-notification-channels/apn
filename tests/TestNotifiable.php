<?php

namespace NotificationChannels\Apn\Tests;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * @return array
     */
    public function routeNotificationForApn()
    {
        return [
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd8',
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd9',
        ];
    }

    /**
     * @return array
     */
    public function routeNotificationForApnVoip()
    {
        return [
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd1',
            '662cfe5a69ddc65cdd39a1b8f8690647778204b064df7b264e8c4c254f94fdd2',
        ];
    }
}
