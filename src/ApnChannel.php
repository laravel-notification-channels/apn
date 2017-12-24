<?php

namespace NotificationChannels\Apn;

use Exception;
use Illuminate\Events\Dispatcher;
use ZendService\Apple\Apns\Message\Alert;
use Illuminate\Notifications\Notification;
use ZendService\Apple\Apns\Message as Packet;
use ZendService\Apple\Apns\Client\Message as Client;
use NotificationChannels\Apn\Exceptions\SendingFailed;
use Illuminate\Notifications\Events\NotificationFailed;
use ZendService\Apple\Apns\Response\Message as Response;
use ZendService\Apple\Apns\Client\Feedback as FeedbackClient;
use ZendService\Apple\Apns\Response\Feedback as FeedbackResponse;

class ApnChannel
{
    const SANDBOX = 0;
    const PRODUCTION = 1;

    /** @var string */
    protected $environment;

    /** @var string */
    protected $certificate;

    /** @var string|null */
    protected $passPhrase;

    /** @var \ZendService\Apple\Apns\Client\Message */
    protected $client;

    /** @var \ZendService\Apple\Apns\Client\Feedback */
    protected $feedbackClient;

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    /**
     * @param \ZendService\Apple\Apns\Client\Message $client
     * @param \ZendService\Apple\Apns\Client\Feedback $feedbackClient
     * @param \Illuminate\Events\Dispatcher $events
     * @param string $environment
     * @param string $certificate
     * @param string|null $passPhrase
     */
    public function __construct(
        Client $client,
        FeedbackClient $feedbackClient,
        Dispatcher $events,
        $environment,
        $certificate,
        $passPhrase = null
    ) {
        $this->client = $client;
        $this->feedbackClient = $feedbackClient;
        $this->events = $events;
        $this->environment = $environment;
        $this->certificate = $certificate;
        $this->passPhrase = $passPhrase;
    }

    /**
     * Send the notification to Apple Push Notification Service.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Apn\Exceptions\SendingFailed
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('apn');
        if (! $tokens) {
            return;
        }

        $message = $notification->toApn($notifiable);
        if (! $message) {
            return;
        }

        $this->openConnection();

        foreach ($tokens as $token) {
            try {
                $alert = new Alert();
                $alert->setTitle($message->title);
                $alert->setBody($message->body);

                $packet = new Packet();
                $packet->setToken($token);
                $packet->setBadge($message->badge);
                $packet->setSound($message->sound);
                $packet->setCategory($message->category);
                $packet->setContentAvailable($message->contentAvailable);
                $packet->setAlert($alert);
                $packet->setCustom($message->custom);

                $response = $this->client->send($packet);

                if ($response->getCode() !== Response::RESULT_OK) {
                    $this->events->fire(
                        new NotificationFailed($notifiable, $notification, $this, [
                            'token' => $token,
                            'error' => $response->getCode(),
                        ])
                    );
                    //connection is useless so create a new connection
                    $this->closeConnection();
                    $this->openConnection();
                }
            } catch (Exception $e) {
                throw SendingFailed::create($e);
            }
        }

        $this->closeConnection();
    }

    /**
     * Get feedback from the Apple Feedback Service about failed deliveries.
     *
     * @return array|ApnFeedback[]
     * @throws Exceptions\ConnectionFailed
     */
    public function getFeedback()
    {
        $client = $this->feedbackClient;

        try {
            $client->open($this->environment, $this->certificate, $this->passPhrase);
        } catch (Exception $exception) {
            throw Exceptions\ConnectionFailed::create($exception);
        }

        $feedback = [];

        /** @var FeedbackResponse $response */
        foreach ($client->feedback() as $response) {
            $feedback[] = new ApnFeedback($response->getToken(), $response->getTime());
        }

        $client->close();

        return $feedback;
    }

    /**
     * Open the connection.
     *
     * @throws \NotificationChannels\Apn\Exceptions\ConnectionFailed
     */
    private function openConnection()
    {
        try {
            $this->client->open($this->environment, $this->certificate, $this->passPhrase);
        } catch (Exception $exception) {
            throw Exceptions\ConnectionFailed::create($exception);
        }
    }

    /**
     * Close the connection.
     */
    private function closeConnection()
    {
        $this->client->close();
    }
}
