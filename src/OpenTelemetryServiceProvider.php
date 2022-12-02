<?php

namespace Spatie\OpenTelemetry;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OpenTelemetry\Drivers\Driver;
use Spatie\OpenTelemetry\Drivers\MultiDriver;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\Measure;
use Spatie\OpenTelemetry\Support\Samplers\Sampler;
use Spatie\OpenTelemetry\Support\StopWatch;

class OpenTelemetryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-open-telemetry')
            ->hasConfigFile();
    }

    public function bootingPackage()
    {
        $this->app->bind(Sampler::class, config('open-telemetry.sampler'));
        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(StopWatch::class, config('open-telemetry.stop_watch'));

        $this->app->singleton(Measure::class, function () {
            $shouldSample = app(Sampler::class)->shouldSample();
            $configuredMultiDriver = $this->getMultiDriver();

            return new Measure($configuredMultiDriver, $shouldSample);
        });

        if (config('open-telemetry.queue.make_queue_trace_aware')) {
            /** @var \Spatie\OpenTelemetry\Actions\MakeQueueTraceAwareAction $action */
            $action = app(config('open-telemetry.actions.make_queue_trace_aware'));

            $action->execute();
        }
    }

    protected function getMultiDriver(): MultiDriver
    {
        $multiDriver = new MultiDriver();

        collect(config('open-telemetry.drivers'))
            ->map(function ($value, $key) {
                $driverClass = $key;
                $config = $value;

                return app($driverClass)->configure($config);
            })
            ->each(fn (Driver $driver) => $multiDriver->addDriver($driver));

        return $multiDriver;
    }
}
