<?php

namespace NotificationChannels\Apn\Console;

use Notification;
use Illuminate\Console\Command;
use NotificationChannels\Apn\Notifications\ApnTestNotification;

class ApnSendTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apn:test 
                            {--T|token= : The token of the device that the PN will be sent to.} 
                            {--t|title=Test APN Notification : The title of the PN.} 
                            {--m|message=This is a test PN sent from the console. : The message body for the PN.} 
                            {--b|badge=1 : The number that will be displayed in the app\'s badge notification.}
                            {--s|sound=default : The name of the sound that should be played with the notification.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test push notification using the APN service.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($errors = $this->optionsAreInvalid()) {
            foreach ($errors as $error) {
                $this->error($error);
            }
            return;
        }

        $this->sendNotification();
        $this->info('Push notification sent.');
    }

    /**
     * Validate whether if the options passed in the command
     * are valid. An array of errors is returned if any
     * options fail the checks. Null is returned if
     * the options are all valid.
     *
     * @param array $errors
     * @return array|null
     */
    private function optionsAreInvalid(array $errors = [])
    {
        if (!$this->option('token')) {
            $errors[] = 'No device token (--token) is specified.';
        }

        if ($this->option('badge') != '0' && !(int)$this->option('badge')) {
            $errors[] = 'The badge number (--badge) must be an integer.';
        }

        return $errors ?? null;
    }

    /**
     * Trigger the notification to send to the testing device.
     *
     * @return void
     */
    private function sendNotification()
    {
        Notification::route('apn', $this->option('token'))
            ->notify(new APNTestNotification(
                $this->option('title'),
                $this->option('message'),
                (int)$this->option('badge'),
                $this->option('sound')
            ));
    }
}
