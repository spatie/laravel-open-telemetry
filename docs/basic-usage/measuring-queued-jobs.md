---
title: Measuring queued jobs
weight: 3
---

Whenever a request starts, the package will automatically generate a trace id. This trace id will be used in a spans you start with `Measure::start($name)`. 

When that request dispatched a queued job, the package will make sure that any spans you start within that queued job will use the same trace id. 

Because the spans within a request and the queued jobs it dispatch is the same, reporting tools like Jaeger are able to combine them.

As a package user, you don't have to think about this at all. Just perform measurements with `Measure::start()` and the package will take care the expected traces and spans are sent.

## Configuring which jobs are trace aware

By default, all jobs will have the same trace id as the request (or parent job) that dispatch them. 

### Automatically measure all jobs

The package will also automatically start tags, so you can see all jobs in a trace in a tool like Jaeger, so you don't have to add an `Measure::start()` calls manually. If you don't want to start a tag in each job automatically, set the `queue.all_jobs_auto_start_a_span` config value to `false` in the `open-telemetry` config file. 

### 

If you don't want a job to be aware of the trace id of the request or parent job that dispatched the job, set the `queue.all_jobs_are_trace_aware_by_default` config value to `false` in the `open-telemetry` config file.  

If you want to make a particular job trace aware while that setting is set to false, then let the job implement the empty `Spatie\OpenTelemetry\Jobs\TraceAware` marker interface

```php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;use Spatie\OpenTelemetry\Jobs\TraceAware;

class MyJob implements ShouldQueue, TraceAware
{
    // implement job
}
```




