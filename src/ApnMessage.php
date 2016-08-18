<?php

namespace Fruitcake\NotificationChannels\Apn;

class ApnMessage
{

    const PRIORITY_NORMAL = 5;
    const PRIORITY_HIGH = 10;

    /**
     * The body of the notification.
     *
     * @var string
     */
    public $body;

    /**
     * The badge of the notification.
     *
     * @var integer
     */
    public $badge;

    /**
     * The priority of the notification.
     * @warning UNUSED
     *
     * @var integer
     */
    public $priority = self::PRIORITY_NORMAL;

    /**
     * Additional data of the notification.
     *
     * @var array
     */
    public $data = [];


    /**
     * Set the body of the notification.
     *
     * @param string $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the message of the notification.
     *
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the badge of the notification.
     *
     * @param integer $badge
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the priority of the notification.
     *
     * @param integer $priority
     * @return $this
     */
    public function priority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Add data to the notification.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function data($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Override the data of the notification.
     *
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add an action to the notification
     *
     * @param string $action
     * @param mixed $params
     * @return $this
     */
    public function action($action, $params = null)
    {
        return $this->data('action', [
            'action' => $action,
            'params' => $params
        ]);
    }

}