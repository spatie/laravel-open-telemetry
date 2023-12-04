<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Support\Samplers\NeverSampler;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeIdGenerator;
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

it('will not send any payloads when we are not sampling', function () {
    config()->set('open-telemetry.sampler', NeverSampler::class);

    $this->rebindClasses();

    Measure::start('my-measure');

    Measure::stop('my-measure');

    $payloads = $this->sentRequestPayloads();

    expect($payloads['sentSpans'])->toHaveCount(0);
});

it('can accept extra merge fields when starting a span that will be added to the sent span', function () {
    Measure::start('my-measure', [
        'extraName' => 'extraValue',
    ]);

    Measure::stop('my-measure');

    $payloads = $this->sentRequestPayloads();

    expect(Arr::get($payloads, 'sentSpans.0.extraName'))->toBe('extraValue');
});

it('can accept extra merge fields when ending a span that will be merge to the sent span', function () {
    Measure::start('my-measure', [
        'extraName' => 'extraValue',
    ]);

    Measure::stop('my-measure', [
        'extraName' => 'extraValueOverridden',
        'another' => 'anotherValue',
    ]);

    $payloads = $this->sentRequestPayloads();

    expect(Arr::get($payloads, 'sentSpans.0.extraName'))->toBe('extraValueOverridden');
    expect(Arr::get($payloads, 'sentSpans.0.another'))->toBe('anotherValue');
});
