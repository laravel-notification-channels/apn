<?php

namespace NotificationChannels\Apn\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApnTestNotification extends Notification
{
    use Queueable;

    /**
     * The body of the test notification that will
     * be sent.
     *
     * @var string
     */
    private $notificationBody;

    /**
     * The title of the test notification that will
     * be sent.
     *
     * @var string
     */
    private $notificationTitle;

    /**
     * The badge number that will be sent with the
     * notification.
     *
     * @var integer
     */
    private $badgeNumber;

    /**
     * The sound that will be played when the device
     * receives the notification.
     *
     * @var string
     */
    private $sound;

    /**
     * NewChatMessage constructor.
     *
     * @param string $notificationTitle
     * @param string $notificationBody
     * @param int $badgeNumber
     * @param string $sound
     */
    public function __construct(string $notificationTitle, string $notificationBody, int $badgeNumber, string $sound = 'default')
    {
        $this->notificationBody = $notificationBody;
        $this->notificationTitle = $notificationTitle;
        $this->badgeNumber = $badgeNumber;
        $this->sound = $sound;
    }

    /**
     * Set the notification route as 'ApnChannel'.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [ApnChannel::class];
    }

    /**
     * Use the APN service to send a push notification
     * to any iOS devices.
     *
     * @param $notifiable
     * @return ApnMessage
     */
    public function toApn($notifiable): ApnMessage
    {
        return ApnMessage::create()
            ->badge($this->badgeNumber)
            ->title($this->notificationTitle)
            ->body($this->notificationBody)
            ->sound($this->sound);
    }
}