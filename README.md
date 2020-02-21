## Laravel APN (Apple Push) Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/apn.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/apn)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/apn/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/apn)
[![StyleCI](https://styleci.io/repos/66449499/shield)](https://github.styleci.io/repos/66449499)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/apn.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/apn)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/apn/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/apn/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/apn.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/apn)

This package makes it easy to send notifications using Apple Push (APN) with Laravel.

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

    composer require laravel-notification-channels/apn

### Setting up the APN service

Before using the APN Service, [enable Push Notifications in your app](https://help.apple.com/xcode/mac/current/#/devdfd3d04a1). Then [create a APNS key under Certificates, Identifiers & Profiles](https://developer.apple.com/account/resources/authkeys/list) to generate a Key ID and .p8 file.

Collect your Key ID, as well as your Team ID (displayed at the top right of the Apple Developer page) and app bundle ID and configure as necessary in `config/broadcasting.php`.

```php
'connections' => [
    'apn' => [
        'key_id' => env('APN_KEY_ID'),
        'team_id' => env('APN_TEAM_ID'),
        'app_bundle_id' => env('APN_BUNDLE_ID'),
        'private_key_content' => env('APN_PRIVATE_KEY'),
        'environment' => \NotificationChannels\Apn\ApnChannel::PRODUCTION,
    ],
]
```

See the [`pushok` docs](https://github.com/edamov/pushok) for more information about what arguments can be supplied to the client - for example you can also use `private_key_path` and `private_key_secret`.

## Usage

You can now send messages to APN by creating a ApnMessage:

```php
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [ApnChannel::class];
    }

    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('Account approved')
            ->body("Your {$notifiable->service} account was approved!");
    }
}
```

To see more of the methods available to you when creating a message please see the [`ApnMessage` source](https://github.com/laravel-notification-channels/apn/blob/master/src/ApnMessage.php).

In your `notifiable` model, make sure to include a `routeNotificationForApn()` method, which return one or an array of tokens.

```php
public function routeNotificationForApn()
{
    return $this->apn_token;
}
```

### Per-message configuration

If you need to provide a custom configuration for a message you can provide an instance of a [Pushok](https://github.com/edamov/pushok) client and it will be used instead of the default one.

```php
$customClient = new Pushok\Client(new Pushok\Token($options));

return ApnMessage::create()
    ->title('Account approved')
    ->body("Your {$notifiable->service} account was approved!")
    ->via($customClient)
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email info@fruitcake.nl instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Fruitcake](https://github.com/fruitcake)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
