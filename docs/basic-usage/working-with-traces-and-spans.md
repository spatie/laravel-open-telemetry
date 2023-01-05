---
title: Working with traces and spans
weight: 2
---

There are two main Open Telemetry metrics that this package supports:

1. Traces: a distributed trace is a set of events, triggered as a result of a single logical operation, consolidated across various components of an application.  A trace usually originates as a request somewhere and encompasses all jobs, requests and other systems that the request touches. Imagine that your application accepts a password reset request and subsequently sends a password reset mail via a queued job. The Open Telemetry trace would then show that request, and the queued job combined.

2. Spans: a span is an operation contained within a trace. It consists of an ID, the trace ID it belongs to, a name, start and finish timestamps, optional tags and finally, an optional parent span id (because spans can be nested).

You can start and stop spans using the following methods:

```php
Measure::start('parent');

// something that needs to be measured

Measure::start('child');
```

Behind the scenes a unique trace ID will be generated at the start of a request. When you call `Measure::start()` a span will be started that will get that trace id injected.

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

