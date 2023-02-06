<?php

namespace Spatie\OpenTelemetry;

use Illuminate\Http\Client\PendingRequest;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OpenTelemetry\Drivers\Driver;
use Spatie\OpenTelemetry\Drivers\MultiDriver;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\Measure;
use Spatie\OpenTelemetry\Support\Samplers\Sampler;
use Spatie\OpenTelemetry\Support\Stopwatch;

class OpenTelemetryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-open-telemetry')
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('spatie/laravel-open-telemetry');
            });
    }

    public function bootingPackage()
    {
        $this->app->bind(Sampler::class, config('open-telemetry.sampler'));
        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(Stopwatch::class, config('open-telemetry.stopwatch'));

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

        $this->addWithTraceMacro();

        if (config('open-telemetry.automatically_trace_requests')) {
            Facades\Measure::start('web-request');
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

    protected function addWithTraceMacro(): self
    {
        PendingRequest::macro('withTrace', function () {
            if ($span = app(Measure::class)->currentSpan()) {
                $headers['traceparent'] = sprintf(
                    '%s-%s-%s-%02x',
                    '00',
                    $span->trace()->id(),
                    $span->id(),
                    $span->flags(),
                );

                /** @var PendingRequest $this */
                $this->withHeaders($headers);
            }

            return $this;
        });

        return $this;
    }
}
