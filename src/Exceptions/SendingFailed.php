<?php

namespace Fruitcake\NotificationChannels\Apn\Exceptions;

class SendingFailed extends \Exception
{
    /**
     * @param \Exception $e
     * @return SendingFailed
     */
    public static function create($e)
    {
        return new static("Cannot send message to APNs: ". $e->getMessage(), 0, $e);
    }
}