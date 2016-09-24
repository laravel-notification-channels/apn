<?php

namespace NotificationChannels\Apn\Exceptions;

use Exception;

class SendingFailed extends Exception
{
    /**
     * @param \Exception $exception
     *
     * @return \NotificationChannels\Apn\Exceptions\SendingFailed
     */
    public static function create($exception)
    {
        return new static("Cannot send message to APNs: {$exception->getMessage()}", 0, $exception);
    }
}
