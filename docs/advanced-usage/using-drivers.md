---
title: Using drivers
weight: 1
---

Collected Open Telemetry metrics need to be sent to an external service to be collected, aggregated and displayed. How they are sent is handled by different drivers. By default, we use the `Http` driver, which is configured in the `drivers` key of the `config/open-telemetry.php` config file.

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

Other drivers that are available are the `\Spatie\OpenTelemtry\Drivers\MemoryDriver` and the `\Spatie\OpenTelemetry\Drivers\MultiDriver`.

## Creating a custom driver

For fine-grained control or crafty ideas, you can use a custom driver. A driver is any class that implements `Spatie\OpenTelemetry\Drivers\Driver`. This is what this interface looks like:

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

The `configure` method will be called when the driver is instantiated. All options set in the config file, will be passed to the driver. The `sendSpan` method will be called whenever a span (measurement) should be sent to the reporting backend. 

As example, you can look at [the source of the `HttpDriver`](https://github.com/spatie/laravel-open-telemetry/blob/5365b3a22f93f31465e6a449e83f30220a5ec8d7/src/Drivers/HttpDriver.php) which ships with this package.
