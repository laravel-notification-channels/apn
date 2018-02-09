<?php

namespace NotificationChannels\Apn;

use Illuminate\Support\ServiceProvider;
use ZendService\Apple\Apns\Client\Message;
use ZendService\Apple\Apns\Client\Feedback;

class ApnServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $credentials = $this->app->make(ApnCredentials::class);

        $this->app->when(ApnChannel::class)
            ->needs(Message::class)
            ->give(function ($app) use ($credentials) {
                $client = new Message;

                try {
                    $client->open(
                        $credentials->environment(),
                        $credentials->certificate(),
                        $credentials->passPhrase()
                    );
                } catch (Exception $exception) {
                    throw ConnectionFailed::create($exception);
                }

                return $client;
            });

        $this->app->when(FeedbackService::class)
            ->needs(Feedback::class)
            ->give(function ($app) use ($credentials) {
                $client = new Feedback;

                try {
                    $client->open(
                        $credentials->environment(),
                        $credentials->certificate(),
                        $credentials->passPhrase()
                    );
                } catch (Exception $exception) {
                    throw ConnectionFailed::create($exception);
                }

                return $client;
            });
    }
}
