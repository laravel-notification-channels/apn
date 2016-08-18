<?php

namespace Fruitcake\NotificationChannels\Apn\Exceptions;

class ConnectionFailed extends \Exception
{
    /**
     * @var \Exception
     */
    public $original;

    /**
     * @param \Exception $original
     * @return ConnectionFailed
     */
    public static function create($original)
    {
        $exception = new static("Cannot connect to APNs: ". $original->getMessage());
        $exception->original = $original;
        return $exception;
    }
}