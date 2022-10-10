<?php

namespace Spatie\OpenTelemetry;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\OpenTelemetry\Commands\OpenTelemetryCommand;

class OpenTelemetryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-open-telemetry')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-open-telemetry_table')
            ->hasCommand(OpenTelemetryCommand::class);
    }
}
