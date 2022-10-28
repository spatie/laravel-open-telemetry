<?php

use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeSpanTagProvider;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeTraceTagProvider;

it('can add extra tags on the trace level', function () {
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

it('can add extra tags on the span level', function () {
    config()->set('open-telemetry.span_tag_providers', [FakeSpanTagProvider::class]);

    Measure::startTrace();

    Measure::start('first');
    Measure::stop('first');

    Measure::start('second');
    Measure::stop('second');

    $payloads = $this->sentRequestPayloads()['sentSpans'];

    expect($payloads[0]['tags']['my-span-tag'])->toBeString()
        ->and($payloads[1]['tags']['my-span-tag'])->toBeString();

    expect($payloads[0]['tags']['my-span-tag'])->not()->toEqual($payloads[1]['tags']['my-span-tag']);
});

it('can add tags on a span', function() {
    Measure::startTrace();

    Measure::start('first')->tags(['extra-name' => 'extra-value']);
    Measure::stop('first');

    Measure::start('second');
    Measure::stop('second');

    $payloads = $this->sentRequestPayloads()['sentSpans'];

    expect($payloads[0]['tags']['extra-name'])->toEqual('extra-value');
    expect($payloads[1]['tags'])->not()->toHaveKey('extra-name');
});
