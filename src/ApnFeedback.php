<?php

namespace NotificationChannels\Apn;

class ApnFeedback
{
    /**
     * The token for the feedback.
     *
     * @var string
     */
    public $token;

    /**
     * The timestamp of the feedback.
     *
     * @var int
     */
    public $timestamp;

    /**
     * Create new feedback instance.
     *
     * @param  string  $token
     * @param  int  $timestamp
     */
    public function __construct($token, $timestamp)
    {
        $this->token = $token;
        $this->timestamp = $timestamp;
    }
}
