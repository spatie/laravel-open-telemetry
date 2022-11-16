---
title: Using drivers
weight: 1
---

How measurements are sent to a back end like Jaeger is handled via a driver. By default, we use the `Http` driver, which is configured in the `drivers` key of the `open-telemetry` config file.

```
/*
 * A driver is responsible for transmitting any measurements.
 */
'drivers' => [
    Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
        'url' => 'http://localhost:9412/api/v2/spans',
    ],
],
```

## Creating a custom driver

For fine-grained control, you can use a custom driver. A driver is any class that implements `Spatie\OpenTelemetry\Drivers\Driver`. This is what that interface looks like:

```php
namespace Spatie\OpenTelemetry\Drivers;

use Spatie\OpenTelemetry\Support\Span;

interface Driver
{
    /**
     * All options set for this driver in the config file will be passed
     * to this method.
     *
     * @param array<string, string> $options
     *
     * @return $this
     */
    public function configure(array $options): self;

    public function sendSpan(Span $span);
}
```

The `configure` method will be called when the driver is instantiated. All options set in the config file, will be passed to the driver. The `sendSpan` method will be called whenever a span should be sent to the reporting backend. 

As example, you can look at [the source of the `HttpDriver`](https://github.com/spatie/laravel-open-telemetry/blob/5365b3a22f93f31465e6a449e83f30220a5ec8d7/src/Drivers/HttpDriver.php) which ships with this package.
