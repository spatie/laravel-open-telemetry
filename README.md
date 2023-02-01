# Use Open Telemetry in your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-open-telemetry.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-open-telemetry)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-open-telemetry/run-tests.yml?branch=main&label=Tests&style=flat-square)](https://github.com/spatie/laravel-open-telemetry/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-open-telemetry/fix-php-code-style-issues.yml?branch=main&label=Code%20Style&style=flat-square)](https://github.com/spatie/laravel-open-telemetry/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-open-telemetry.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-open-telemetry)

**THIS PACKAGE IS IN DEVELOPMENT, DON'T USE IT IN PRODUCTION (YET)**

Measuring performance and tracking bugs is typically done inside a single web request or job. But what if you want to see the performance or flow of a web request together with all the jobs it dispatched?

Open Telemetry, or OTel for short, is a collection of tools, APIs and SDKs to collect information on how an entire system is behaving. A "system" can be a single application, or a group of applications (or queued jobs) that are working together.

Using the laravel-open-telemetry package you can easily measure performance of a Laravel powered system. It can transmit the results to a tracing tool like [Jaeger](https://www.jaegertracing.io) or [Aspecto](https://www.aspecto.io).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-open-telemetry.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-open-telemetry)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/laravel-open-telemetry).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
