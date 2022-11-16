<?php

use Spatie\OpenTelemetry\Support\ParsedTraceParentHeaderValue;

it('can parse a correct header value', function () {
    $parsed = ParsedTraceParentHeaderValue::make('00-80e1afed08e019fc1110464cfa66635c-7a085853722dc6d2-01');

    expect($parsed)
        ->toBeInstanceOf(ParsedTraceParentHeaderValue::class)
        ->version->toBe('00')
        ->traceId->toBe('80e1afed08e019fc1110464cfa66635c')
        ->spanId->toBe('7a085853722dc6d2')
        ->flags->toBe('01');
});

it('will return null if the header is not valid', function (string $value) {
    $parsed = ParsedTraceParentHeaderValue::make($value);

    expect($parsed)->toBeNull();
})->with([
    [''],
    ['00-80e1afed08e019fc1110464cfa66635c'],
    ['xx-80e1afed08e019fc1110464cfa66635c-7a085853722dc6d2-01'],
    ['00-xxxx-7a085853722dc6d2-01'],
    ['00-80e1afed08e019fc1110464cfa66635c-xxxxxx-01'],
    ['00-80e1afed08e019fc1110464cfa66635c-xxxx'],
]);
