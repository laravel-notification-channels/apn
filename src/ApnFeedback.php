<?php

namespace NotificationChannels\Apn;

class ApnFeedback
{
    protected $token;
    protected $time;

    /**
     * @param string $token
     * @param int $time
     */
    public function __construct($token, $time)
    {
        $this->token = $token;
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->time;
    }
}
