---
title: Continuing tracing with other systems
weight: 2
---

Using open telemetry, a trace can start and stop on different systems. The package is able to handle this.

## Accepting a trace from another system to your Laravel app

If your Laravel app is used in a bigger system, where one of the other apps started a trace, your Laravel app might get called by that other apps with [a `traceparent` header](https://uptrace.dev/opentelemetry/opentelemetry-traceparent.html). This header contains the id of trace that was started.

To have all measurements you take use that trace id from the header, you can use the `ContinueTrace` middleware. This middleware will look for incoming requests that have a `traceparent` header and use the given trace id.

To use the `ContinueTrace` middleware, register it in your HTTP kernel.

```php
namespace App\Http\Middleware;

use Spatie\OpenTelemetry\Http\Middleware\ContinueTrace;

class Kernel extends HttpKernel
{
    protected $middleware = [
        ContinueTrace::class,
        
        // other middleware
    ];
}
```

## Sending trace information from your Laravel app to another system

If your Laravel app calls other system using Laravel's built-in `Http` client, you can call the `withTrace` method. This will add a `traceparent` header containing the trace and span ids.

```php
use Illuminate\Support\Facades\Http;

Http::withTrace()->post('https://example.com');
```

