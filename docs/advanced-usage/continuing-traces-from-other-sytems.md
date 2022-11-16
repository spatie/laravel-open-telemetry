---
title: Continuing traces from other systems
weight: 1
---

If your Laravel app is used in a bigger system, where one of the other apps started a trace, your Laravel app might get called by that other apps with [a `traceparent` header](https://uptrace.dev/opentelemetry/opentelemetry-traceparent.html). This header contains the id of trace that was started.

To have all measurements you take use that trace id from the header, you can use the `ContinueTrace` middleware. This middleware will look for incoming requests that have a `traceparent` header and use the given trace id.

To use the `ContinueTrace` middleware, register it in your HTTP kernel.

```php
namespace App\Http;

use Spatie\OpenTelemetry\Http\Middleware\ContinueTrace;class Kernel extends HttpKernel
{
    protected $middleware = [
        ContinueTrace::class
        // ...

    ];
}
```


