<?php

namespace Fruitcake\NotificationChannels\Apn\Exceptions;

class SendingFailed extends \Exception
{
    /**
     * @var \Exception
     */
    public $original;

    /**
     * @param \Exception $original
     * @return SendingFailed
     */
    public static function create($original)
    {
        $exception = new static("Cannot send message to APNs: ". $original->getMessage());
        $exception->original = $original;
        return $exception;
    }
}