<?php

namespace Fruitcake\NotificationChannels\Apn;

class PushMessage
{

    /**
     * The title of the notification.
     *
     * @var string
     */
    public $title;

    /**
     * The message of the notification.
     *
     * @var string
     */
    public $message;

    /**
     * The badge of the notification.
     *
     * @var integer
     */
    public $badge;

    /**
     * Additional data of the notification.
     *
     * @var array
     */
    public $data = [];


    /**
     * Set the title of the notification.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

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