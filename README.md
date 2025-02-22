# A filament plugin that auto logs out your users if they are idle. Works with multiple tabs.

<br>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/niladam/filament-auto-logout.svg?style=flat-square)](https://packagist.org/packages/niladam/filament-auto-logout)
[![Total Downloads](https://img.shields.io/packagist/dt/niladam/filament-auto-logout.svg?style=flat-square)](https://packagist.org/packages/niladam/filament-auto-logout)


## Installation

You can install the package via composer:

```bash
composer require niladam/filament-auto-logout
```

## Install the package

```bash
php artisan filament-auto-logout:install
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-auto-logout-config"
```

This is the contents of the published config file:

```php
use Carbon\Carbon;
use Filament\View\PanelsRenderHook;

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
     * Where should the badge be rendered?
     *
     * @see https://filamentphp.com/docs/3.x/support/render-hooks#available-render-hooks for a list of supported hooks.
     */
    'location' => env('FILAMENT_AUTO_LOGOUT_LOCATION', PanelsRenderHook::GLOBAL_SEARCH_BEFORE),
];
```

## Usage

### Quick Usage:

```php
$panel
    ->plugins([
        AutoLogoutPlugin::make(),
    ]);
```

### Customised Usage

```php
use Carbon\Carbon;
use Filament\Support\Colors\Color;
use Niladam\FilamentAutoLogout\AutoLogoutPlugin;

$panel
    ->plugins([
        AutoLogoutPlugin::make()
            ->color(Color::Emerald)                         // Set the color. Defaults to Zinc
            ->disableIf(fn () => auth()->id() === 1)        // Disable the user with ID 1
            ->logoutAfter(Carbon::SECONDS_PER_MINUTE * 5)   // Logout the user after 5 minutes
            ->withoutWarning()                              // Disable the warning before logging out
            ->withoutTimeLeft()                             // Disable the time left
            ->timeLeftText('Oh no. Kicking you in...')      // Change the time left text
            ->timeLeftText('')                              // Remove the time left text (displays only countdown)
    ]);
```

## Translations

This package has multi-language support. So you will have to first publish the translations using:

```bash
php artisan vendor:publish --tag="filament-auto-logout-translations"
```

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
