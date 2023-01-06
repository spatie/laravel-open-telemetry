<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\OpenTelemetry\Drivers\MemoryDriver;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\OpenTelemetryServiceProvider;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\Samplers\AlwaysSampler;
use Spatie\OpenTelemetry\Support\Samplers\Sampler;
use Spatie\OpenTelemetry\Support\Stopwatch;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeIdGenerator;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeStopwatch;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeTagsProvider;

class TestCase extends Orchestra
{
    protected MemoryDriver $memoryDriver;

    protected function setUp(): void
    {
        parent::setUp();

        FakeIdGenerator::reset();

        config()->set('open-telemetry.id_generator', FakeIdGenerator::class);
        config()->set('open-telemetry.stopwatch', FakeStopwatch::class);
        config()->set('open-telemetry.trace_tag_providers', [FakeTagsProvider::class]);

        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(Stopwatch::class, config('open-telemetry.stopwatch'));
        $this->app->bind(Sampler::class, AlwaysSampler::class);

        $this->memoryDriver = new MemoryDriver();

        Measure::setDriver($this->memoryDriver);
    }

    protected function getPackageProviders($app)
    {
        return [
            OpenTelemetryServiceProvider::class,
        ];
    }

    public function tempFile(string $fileName): string
    {
        return __DIR__."/temp/{$fileName}";
    }

    public function sentRequestPayloads(): array
    {
        return $this->memoryDriver->allPayloads();
    }

    public function rebindClasses()
    {
        Measure::clearResolvedInstances();
        (new OpenTelemetryServiceProvider($this->app))->bootingPackage();
    }
}
