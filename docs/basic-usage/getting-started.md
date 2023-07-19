---
title: Getting started
weight: 1
---

Measuring performance and tracking bugs is typically done inside a single web request or job. But what if you want to see the performance or flow of a web request together with all the jobs it dispatched?

Open Telemetry, or OTel for short, is a collection of tools, APIs and SDKs to collect information on how an entire system is behaving. A "system" can be a single application, or a group of applications (or queued jobs) that are working together.

There are two main Open Telemetry metrics that this package supports:

1. Traces: a distributed trace is a set of events, triggered as a result of a single logical operation, consolidated across various components of an application.  A trace usually originates as a request somewhere and encompasses all jobs, requests and other systems that the request touches. Imagine that your application accepts a password reset request and subsequently sends a password reset mail via a queued job. The Open Telemetry trace would then show that request, and the queued job combined. 

2. Spans: a span is an operation contained within a trace. It consists of an ID, the trace ID it belongs to, a name, start and finish timestamps, optional tags and finally, an optional parent span id (because spans can be nested).

You can learn more about the Open Telemetry high level concepts [in their documentation](https://opentelemetry.io/docs/reference/specification/overview/).

Using the laravel-open-telemetry package, you can easily measure performance of a Laravel powered system. It can transmit the results to a tracing tool like [Jaeger](https://www.jaegertracing.io) or [Aspecto](https://www.aspecto.io).

In your Laravel application, you can start and stop measurements using the `Measure` facade. You can nest measurements however deep you like.

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

Behind the scenes a unique trace ID will be generated at the start of a request. When you call `Measure::start()` a span will be started and linked to that trace.

Here's what that will look like in Jaeger:

![screenshot](https://spatie.be/docs/laravel-open-telemetry/v1/images/trace.jpg)

The real value of this package comes when also using it to measure an entire process that includes its web requests, one or more jobs and requests to additional services.

```php
Measure::start('my-web-request');

Measure::stop('my-web-request');

dispatch(new MyJob()); // let's imagine that this job has a `sleep(1)` in its `handle` method.
```

The package will automatically measure any jobs. Any measurements in the job will be associated with the web request the job was dispatched from.

This is what it would look like in Jaeger:

![screenshot](https://spatie.be/docs/laravel-open-telemetry/v1/images/trace-with-job.jpg)
