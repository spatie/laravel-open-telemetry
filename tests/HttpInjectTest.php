<?php

use Spatie\OpenTelemetry\Facades\Measure;

it('injects current span context name to Laravel HTTP (outgoing) requests', function () {
    Http::fake();

    $parentSpanTraceContextID = Measure::start('parent')->toTraceContextID();

    Http::withTrace()->post('http://example.com/first');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($parentSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $parentSpanTraceContextID);
    });

    $childSpanTraceContextID = Measure::start('second')->toTraceContextID();

    Http::withTrace()->post('http://example.com/second');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($childSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $childSpanTraceContextID);
    });

    Measure::stop('second');

    Http::withTrace()->post('http://example.com/third');

    Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($parentSpanTraceContextID) {
        return $request
            ->hasHeader('traceparent', $parentSpanTraceContextID);
    });

    Measure::stop('first');
});
