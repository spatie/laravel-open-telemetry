<?php

use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeTraceTagProvider;

it('can add extra tags on the trace level', function() {
    config()->set('open-telemetry.trace_tag_providers', [FakeTraceTagProvider::class]);

    Measure::startTrace();

    Measure::start('first');
    Measure::stop('first');

    Measure::start('second');
    Measure::stop('second');

    $payloads = $this->sentRequestPayloads()['sentSpans'];

    expect($payloads[0]['tags']['my-trace-tag'])->toBeString()
        ->and($payloads[1]['tags']['my-trace-tag'])->toBeString();

    expect($payloads[0]['tags']['my-trace-tag'])->toEqual($payloads[1]['tags']['my-trace-tag']);

});
