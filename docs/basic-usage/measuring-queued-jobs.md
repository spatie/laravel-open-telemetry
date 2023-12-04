---
title: Measuring queued jobs
weight: 3
---

Whenever a request gets processed, the package will automatically generate and keep track of a trace ID. This trace ID will be used in spans you start with `Measure::start($name)`. 

When that request dispatches a queued job, the package will make sure that any spans you start within that queued job will also use that same trace ID. This way, reporting tools like Jaeger are able to combine all spans for a trace by looking at the trace ID.

As a package user, you don't have to think about this at all. Just perform measurements with `Measure::start()` and `Measure::stop()`. The package will take care of sending the expected traces and spans.

## Configuring which jobs are trace aware

By default, all jobs will be trace aware, and thus use the same trace ID as the request (or parent job) that dispatched them. In the `config/open-telemetry.php` config file, you can configure configure additional options like not tracing jobs at all, or only tracing specific jobs.

If you don't want a job to be aware of the trace id of the request or parent job that dispatched the job, set the `queue.all_jobs_are_trace_aware_by_default` config value to `false` in the `open-telemetry` config file.

If you want to make a particular job trace aware while that setting is set to false, then let the job implement the empty `Spatie\OpenTelemetry\Jobs\TraceAware` marker interface:

```php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\OpenTelemetry\Jobs\TraceAware;

class MyJob implements ShouldQueue, TraceAware
{
    // implement job
}
```

### Automatically measure all jobs

The package will also automatically create spans for each job. This way you don't have to call `Measure::start()` and `stop()` manually. If you don't want to start a span in each job automatically, set the `queue.all_jobs_auto_start_a_span` config value to `false` in the `config/open-telemetry.php` file.
