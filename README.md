# A filament plugin that auto logs out your users if they are idle. Works with multiple tabs.

<br>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/niladam/filament-auto-logout.svg?style=flat-square)](https://packagist.org/packages/niladam/filament-auto-logout)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/niladam/filament-auto-logout/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/niladam/filament-auto-logout/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/niladam/filament-auto-logout.svg?style=flat-square)](https://packagist.org/packages/niladam/filament-auto-logout)


## Installation

You can install the package via composer:

```bash
composer require niladam/filament-auto-logout
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-auto-logout-config"
```

This is the contents of the published config file:

```php
use Carbon\Carbon;
use Niladam\FilamentAutoLogout\Enums\AutoLogoutPosition;

return [
    /**
     * Disable or enable the plugin
     */
    'enabled' => env('FILAMENT_AUTO_LOGOUT_ENABLED', true),

    /**
     * The duration in seconds your users can be idle before being logged out.
     *
     * The duration needs to be specified in seconds.
     *
     * A sensible default has been set to 15 minutes
     */
    'duration_in_seconds' => env('FILAMENT_AUTO_LOGOUT_DURATION_IN_SECONDS', Carbon::SECONDS_PER_MINUTE * 15),

    /**
     * A notification will be sent to the user before logging out.
     *
     * This sets the seconds BEFORE sending out the notification.
     */
    'warn_before_in_seconds' => env('FILAMENT_AUTO_LOGOUT_WARN_BEFORE_IN_SECONDS', 30),

    /**
     * The plugin comes with a small time left box which will display the time left
     * before the user will be logged out.
     */
    'show_time_left' => env('FILAMENT_AUTO_LOGOUT_SHOW_TIME_LEFT', true),

    /**
     * What should the time left box display before the timer?
     *
     * A default has been set to 'Time left:'
     */
    'time_left_text' => env('FILAMENT_AUTO_LOGOUT_TIME_LEFT_TEXT', 'Time left:'),

    /**
     * The position of the time left box.
     *
     * Defaults to right.
     *
     * Currently only 'right' and 'left' (bottom) are supported
     */
    'time_left_position' => env('FILAMENT_AUTO_LOGOUT_TIME_LEFT_POSITION', AutoLogoutPosition::BOTTOM_RIGHT),
];
```

## Usage

```php
use Carbon\Carbon;
use Niladam\FilamentAutoLogout\AutoLogoutPlugin;

$panel
    ->plugins([
        AutoLogoutPlugin::make()
            ->positionLeft()                                // Align the time left box to the left. Defaults to right.
            ->disableIf(fn () => auth()->id() === 1)        // Disable the user with ID 1
            ->logoutAfter(Carbon::SECONDS_PER_MINUTE * 5)   // Logout the user after 5 minutes
            ->withoutWarning()                              // Disable the warning
            ->withoutTimeLeft()                             // Disable the time left
            ->timeLeftText('Oh no. Kicking you in...')      // Change the time left text
    ]);
```

### Configuration


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Madalin Tache](https://github.com/niladam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
