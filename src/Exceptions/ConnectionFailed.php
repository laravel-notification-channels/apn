<?php

namespace Fruitcake\NotificationChannels\Apn\Exceptions;

class ConnectionFailed extends \Exception
{
    /**
     * @param \Exception $e
     * @return ConnectionFailed
     */
    public static function create($e)
    {
        return new static("Cannot connect to APNs: ". $e->getMessage(), 0, $e);
    }
}