![](https://banners.beyondco.de/UTM%20Forwarder.png?theme=light&packageManager=composer+require&packageName=guiassemany%2Flaravel-utm-forwarder&pattern=architect&style=style_1&description=Keeps+track+of+the+original+UTM+%28or+other+analytics%29+parameters&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/guiassemany/laravel-utm-forwarder.svg?style=flat-square)](https://packagist.org/packages/guiassemany/laravel-utm-forwarder)
[![Total Downloads](https://img.shields.io/packagist/dt/guiassemany/laravel-utm-forwarder.svg?style=flat-square)](https://packagist.org/packages/guiassemany/laravel-utm-forwarder)

Cross domain analytics is hard. This package helps you to keep track of the visitor's original UTM parameters, referer header and other analytics parameters. You can then submit these parameters along with a form submission or add them to a link to another domain you track.

## Installation

You can install the package via composer:

```bash
composer require guiassemany/laravel-utm-forwarder
```

The package works via a middleware that needs to be added to the `web` stack in your `kernel.php` file. Make sure to register this middleware after the `StartSession` middleware.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        // ...
        \Illuminate\Session\Middleware\StartSession::class,
        // ...

        \GuiAssemany\UtmForwarder\Middleware\TrackAnalyticsParametersMiddleware::class,
    ],
];
```

To configure the tracked parameters or how they're mapped on the URL parameters, you can publish the config file using:

```bash
php artisan vendor:publish --provider="GuiAssemany\UtmForwarder\UtmForwarderServiceProvider"
```

This is the contents of the published config file:

```php
return [
    /*
     * These are the analytics parameters that will be tracked when a user first visits
     * the application. The configuration consists of the parameter's key and the
     * source to extract this key from.
     *
     * Available sources can be found in the `\GuiAssemany\UtmForwarder\Sources` namespace.
     */
    'tracked_parameters' => [
        [
            'key' => 'utm_source',
            'source' => GuiAssemany\UtmForwarder\Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_medium',
            'source' => GuiAssemany\UtmForwarder\Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_campaign',
            'source' => GuiAssemany\UtmForwarder\Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_term',
            'source' => GuiAssemany\UtmForwarder\Sources\RequestParameter::class,
        ],
        [
            'key' => 'utm_content',
            'source' => GuiAssemany\UtmForwarder\Sources\RequestParameter::class,
        ],
        [
            'key' => 'referer',
            'source' => GuiAssemany\UtmForwarder\Sources\CrossOriginRequestHeader::class,
        ],
    ],

    /**
     * We'll put the tracked parameters in the session using this key.
     */
    'session_key' => 'tracked_analytics_parameters',

    /*
     * When formatting an URL to add the tracked parameters we'll use the following
     * mapping to put tracked parameters in URL parameters.
     *
     * This is useful when using an analytics solution that ignores the utm_* parameters.
     */
    'parameter_url_mapping' => [
        'utm_source' => 'utm_source',
        'utm_medium' => 'utm_medium',
        'utm_campaign' => 'utm_campaign',
        'utm_term' => 'utm_term',
        'utm_content' => 'utm_content',
        'referer' => 'referer',
    ],
];
```

## Usage

The easiest way to retrieve the tracked parameters is by resolving the `TrackedAnalyticsParameters` class:

```php
use GuiAssemany\UtmForwarder\AnalyticsBag;

app(AnalyticsBag::class)->get(); // returns an array of tracked parameters
```

You can also decorate an existing URL with the tracked parameters. This is useful to forward analytics to another domain you're running analytics on.

```blade
<a href="{{ app(\GuiAssemany\UtmForwarder\AnalyticsTracker::class)->decorateUrl('https://testing.com/') }}">
    Buy this product on our webshop
</a>

Will link to https://testing.com?utm_source=facebook&utm_campaign=blogpost
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- Package built by spatie and [Alex Vanderbist](https://github.com/AlexVanderbist) and all contributors.
- I've just put it here because I want it to be on packagist and make it available for people who want to use it through composer.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
