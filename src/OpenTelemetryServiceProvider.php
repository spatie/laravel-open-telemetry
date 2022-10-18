<?php

namespace Spatie\OpenTelemetry;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OpenTelemetry\Commands\OpenTelemetryCommand;
use Spatie\OpenTelemetry\Drivers\Driver;
use Spatie\OpenTelemetry\Drivers\Multidriver;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\Measure;
use Spatie\OpenTelemetry\Support\StopWatch;
use Spatie\OpenTelemetry\Support\Trace;

class OpenTelemetryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-open-telemetry')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-open-telemetry_table')
            ->hasCommand(OpenTelemetryCommand::class);
    }

    public function bootingPackage()
    {
        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(StopWatch::class, config('open-telemetry.stop_watch'));

        $this->app->singleton(Measure::class, function () {
            $configuredMultiDriver = $this->getMultiDriver();

            return new Measure($configuredMultiDriver);
        });
    }

    protected function getMultiDriver(): Multidriver
    {
        $multiDriver = new Multidriver();

        collect(config('open-telemetry.drivers'))
            ->map(function ($value, $key) {
                $driverClass = $key;
                $config = $value;

                return app($driverClass)->configure($config);
            })
            ->each(fn(Driver $driver) => $multiDriver->addDriver($driver));

        return $multiDriver;
    }
}
