<?php

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\TestJob;
use Spatie\Valuestore\Valuestore;

beforeEach(function () {
    Http::fake();

    $this->valuestore = Valuestore::make($this->tempFile('traceAware.json'))->flush();
});

it('will inject the active trace id in the payload of a job', function () {
    Measure::setTraceId('originalTraceId');

    $job = new TestJob($this->valuestore);
    app(Dispatcher::class)->dispatch($job);

    Measure::setTraceId('otherTraceId');

    $this->artisan('queue:work --once')->assertExitCode(0);

    $activeTraceIdInJob = $this->valuestore->get('activeTraceIdInJob');

    expect($activeTraceIdInJob)->toBe('originalTraceId');
});

it('will auto start span for a job', function () {
    Measure::setTraceId('originalTraceId');

    $job = new TestJob($this->valuestore);
    app(Dispatcher::class)->dispatch($job);

    $this->artisan('queue:work --once')->assertExitCode(0);

    $startedSpanNames = $this->valuestore->get('startedSpansInJob');

    expect($startedSpanNames)->toBe([TestJob::class]);
});
