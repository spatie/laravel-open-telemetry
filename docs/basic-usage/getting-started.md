---
title: Getting started
weight: 1
---

Measuring performance and tracking bugs is typically done inside a single web request or job. But what if you want to see the performance or flow of a web request together with all the jobs it dispatched?

Open Telemetry, or OTel for short, is a collection of tools, APIs and SDKs to collect information on how an entire system is behaving. A "system" can be a single application, or a group of applications (or queued jobs) that are working together.

There are two main Open Telemetry that this package supports:

1. Traces: a distributed trace is a set of events, triggered as a result of a single logical operation, consolidated across various components of an application.  A trace originates usually as a request somewhere and encompasses all jobs and other systems that request touches. Imagine that your application can accept a password reset request and sends a password reset mail via the queue, then the trace would be that request, and the queued job combined. 

- a span: a span is an operation contained within a trace. It consists of an id a name, start and finish datetime, tags, the trace id it belongs to and a parent span id (because spans can be nested).

You can learn more about the Open Telemetry high level concepts [in their documentation](https://opentelemetry.io/docs/reference/specification/overview/).

Using the laravel-open-telemetry package you can easily measure performance of a Laravel powered system. It can transmit the results to a tracing tool like [Jaeger](https://www.jaegertracing.io) or [Aspecto](https://www.aspecto.io).

In your Laravel application, you can use start and stop measurements using the `Measure` facade. You can nest measurements how deep you like.

```php
use Spatie\OpenTelemetry\Facades\Measure;

Measure::start('parent');
sleep(1);
Measure::start('child');

sleep(2);

Measure::stop('child');
sleep(3);
Measure::stop('parent');
```

Behind the scenes a unique trace id will be generated at the start of a request. When you call `Measure::start()` a span will be started that will get that trace id injected.

Here's how that will look like in Jaeger:

![screenshot](https://spatie.be/docs/laravel-open-telemetry/v1/images/trace.jpg)

The real value of this package comes when also using it to measure an entire process the includes the web requests, one or more jobs / services.

```php
Measure::start('my-web-request');

Measure::stop('my-web-request');

dispatch(new MyJob()); // let's image that this jobs has a `sleep(1)` in its `handle` method.
```

The package will automatically measure any jobs. Any measurements in the job will be associated with the web request that dispatch it.

This is how it would look like in Jaeger:

![screenshot](https://spatie.be/docs/laravel-open-telemetry/v1/images/trace-with-job.jpg)
