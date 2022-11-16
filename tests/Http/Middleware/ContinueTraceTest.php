<?php

use Illuminate\Support\Facades\Route;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Http\Middleware\ContinueTrace;

beforeEach(function () {
    Route::any('test-route', fn() => Measure::hasTraceId()
        ? Measure::traceId()
        : 'did not start trace'
    )->middleware(ContinueTrace::class);
});

it('will continue a trace when the traceparent header is set to a valid value', function () {
    $response = $this->post('test-route', headers: ['traceparent' => '00-80e1afed08e019fc1110464cfa66635c-7a085853722dc6d2-01']);

    expect($response->content())->toBe('80e1afed08e019fc1110464cfa66635c');
});

it('will not continue a trace when the traceparent header is set to a invalid value', function () {
    $response = $this->post('test-route', headers: ['traceparent' => '00-80e1afed08e019fc1110464cfa66635cxxx-7a085853722dc6d2-01']);

    expect($response->content())->toBe('did not start trace');
});
