<?php

use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\TestTime\TestTime;
use function Spatie\Snapshots\assertMatchesSnapshot;

beforeEach(function () {
    TestTime::freeze('Y-m-d H:i:s', '2022-01-01 00:00:00');

    Http::fake();
});

it('can measure a single span', function () {
    Measure::start('first');

    TestTime::addSecond();

    Measure::stop('first');

    $payloads = sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});

it('can measure multiple spans', function() {
    Measure::start('first');

    TestTime::addSecond();

    Measure::stop('first');

    Measure::start('second');

    TestTime::addSecond();

    Measure::stop('second');

    $payloads = sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});

it('can measure nested spans', function() {
    Measure::start('parent');

    Measure::start('child');

    Measure::stop('child');

    Measure::stop('parent');

    $payloads = sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});
