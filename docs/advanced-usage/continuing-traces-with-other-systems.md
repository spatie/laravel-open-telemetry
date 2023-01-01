---
title: Continuing tracing with other systems
weight: 2
---

## From other system

If your Laravel app is used in a bigger system, where one of the other apps started a trace, your Laravel app might get called by that other apps with [a `traceparent` header](https://uptrace.dev/opentelemetry/opentelemetry-traceparent.html). This header contains the id of trace that was started.

To have all measurements you take use that trace id from the header, you can use the `ContinueTrace` middleware. This middleware will look for incoming requests that have a `traceparent` header and use the given trace id.

To use the `ContinueTrace` middleware, register it in your HTTP kernel.

```php
namespace App\Http\Middleware;

use Spatie\OpenTelemetry\Http\Middleware\ContinueTrace;

class Kernel extends HttpKernel
{
    protected $middleware = [
        ContinueTrace::class
        // ...

    ];
}
```

## To other system

In another case, if your Laravel app calls other systems, you can inject `traceparent` HTTP Header onto the outgoing request, by calling macro `withTrace` in HTTP Facade.

It automatically adds current span context id to HTTP header for your outgoing request.

```php
use Http;

Http::withTrace()->post('https://example.com')
```

There has variants for header injection, with configuring config:

```php
/*
 * Injectors configure, how span will be injected into other structures
 */
'injectors' => [
    /*
     * Array Injector often be used for configuration of outgoing http requests.
     */
    'array' => [
        'key' => 'traceparent'
    ]
]
```

