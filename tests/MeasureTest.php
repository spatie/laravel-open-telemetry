<?php

use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeIdGenerator;
use function Spatie\Snapshots\assertMatchesSnapshot;
use Spatie\TestTime\TestTime;

beforeEach(function () {
    TestTime::freeze('Y-m-d H:i:s', '2022-01-01 00:00:00');

    Http::fake();


});

it('can measure a single span', function () {
    Measure::start('first');

    TestTime::addSecond();

    Measure::stop('first');

    $payloads = $this->sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});

it('can measure multiple spans', function () {
    FakeIdGenerator::reset();

    Measure::start('first');

    TestTime::addSecond();

    Measure::stop('first');

    Measure::start('second');

    TestTime::addSecond();

    Measure::stop('second');

    $payloads = $this->sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});

it('can measure nested spans', function () {
    Measure::start('parent');

    TestTime::addSecond();

    Measure::start('child');

    TestTime::addSecond();

    Measure::stop('child');

    TestTime::addSecond();

    Measure::stop('parent');

    $payloads = $this->sentRequestPayloads();

    assertMatchesSnapshot($payloads);
});
