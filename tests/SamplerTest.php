<?php

use Spatie\OpenTelemetry\Support\Samplers\AlwaysSampler;
use Spatie\OpenTelemetry\Support\Samplers\LotterySampler;
use Spatie\OpenTelemetry\Support\Samplers\NeverSampler;

test('the AlwaysSampler always returns true', function() {
   expect(app(AlwaysSampler::class)->shouldSample())->toBe(true);
});

test('the NeverSampler always returns false', function() {
    expect(app(NeverSampler::class)->shouldSample())->toBe(false);
});

test('the LotterySampler returns a boolean', function() {
    expect(app(LotterySampler::class)->shouldSample())->toBeBool();
});
