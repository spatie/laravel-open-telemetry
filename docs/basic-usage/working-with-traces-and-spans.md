---
title: Working with traces and spans
weight: 2
---

There are two main Open Telemetry that this package supports:

1. Traces: a distributed trace is a set of events, triggered as a result of a single logical operation, consolidated across various components of an application.  A trace originates usually as a request somewhere and encompasses all jobs and other systems that request touches. Imagine that your application can accept a password reset request and sends a password reset mail via the queue, then the trace would be that request, and the queued job combined.

- a span: a span is an operation contained within a trace. It consists of an id a name, start and finish datetime, tags, the trace id it belongs to and a parent span id (because spans can be nested).

You can start and stop spans using these methods:

```php
Measure::start('parent');

// something that needs to be measured

Measure::start('child');
```

Behind the scenes a unique trace id will be generated at the start of a request. When you call `Measure::start()` a span will be started that will get that trace id injected.

You can also nest these calls however deep you like.

```php
use Spatie\OpenTelemetry\Facades\Measure;

Measure::start('parent');
// measure something
Measure::start('child');

// measure something

Measure::stop('child');
// measure something
Measure::stop('parent');
```

