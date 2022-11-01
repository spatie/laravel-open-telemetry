<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\OpenTelemetry\Drivers\MemoryDriver;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\OpenTelemetryServiceProvider;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\Samplers\AlwaysSampler;
use Spatie\OpenTelemetry\Support\Samplers\Sampler;
use Spatie\OpenTelemetry\Support\StopWatch;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeIdGenerator;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeStopWatch;

class TestCase extends Orchestra
{
    protected MemoryDriver $memoryDriver;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('open-telemetry.id_generator', FakeIdGenerator::class);
        config()->set('open-telemetry.stop_watch', FakeStopWatch::class);

        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(StopWatch::class, config('open-telemetry.stop_watch'));
        $this->app->bind(Sampler::class, AlwaysSampler::class);

        $this->memoryDriver = new MemoryDriver();

        Measure::setDriver($this->memoryDriver);

        FakeIdGenerator::reset();
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
}
