---
title: Continuing tracing with other systems
weight: 2
---

Using Open Telemetry, a trace can start and stop on different systems. For example, imagine a request handled by a webserver, triggering a job on a different queue server. Another example might be if you're building a microservice, an incoming request might already be part of an ongoing trace. The package is able to handle this.

## Accepting a trace from another system to your Laravel app

If your Laravel app is used in a bigger system, where one of the other apps started a trace, your Laravel app might get called by that other apps with [a `traceparent` header](https://uptrace.dev/opentelemetry/opentelemetry-traceparent.html). This header contains the ID of the trace that was previously started on another system.

To automatically discover and use this parent trace ID, you can use the `ContinueTrace` middleware. This middleware will look for incoming requests that have a `traceparent` header and use the given trace ID when available.

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

If your app uses Laravel's built-in `Http` client to call another system, you can call the `withTrace()` method to automatically add a `traceparent` header to the request for the current trace.

```php
use Illuminate\Support\Facades\Http;

Http::withTrace()->post('https://example.com');
```

