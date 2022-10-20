<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport\TestClasses;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\Valuestore\Valuestore;

class TestJob implements ShouldQueue
{
    use InteractsWithQueue;

    public Valuestore $valuestore;

    public function __construct(Valuestore $valuestore)
    {
        $this->valuestore = $valuestore;
    }

    public function handle()
    {
        $this->valuestore->put('traceIdInPayload', $this->job->payload()['traceId'] ?? null);
        $this->valuestore->put('activeTraceIdInJob', Measure::traceId());
    }
}
