<?php

namespace Spatie\OpenTelemetry\Tests\TestSupport;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\OpenTelemetry\OpenTelemetryServiceProvider;
use Spatie\OpenTelemetry\Support\IdGenerator;
use Spatie\OpenTelemetry\Support\StopWatch;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeIdGenerator;
use Spatie\OpenTelemetry\Tests\TestSupport\TestClasses\FakeStopWatch;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('open-telemetry.id_generator', FakeIdGenerator::class);
        config()->set('open-telemetry.stop_watch', FakeStopWatch::class);

        $this->app->bind(IdGenerator::class, config('open-telemetry.id_generator'));
        $this->app->bind(StopWatch::class, config('open-telemetry.stop_watch'));

        FakeIdGenerator::reset();
    }

    protected function getPackageProviders($app)
    {
        return [
            OpenTelemetryServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-open-telemetry_table.php.stub';
        $migration->up();
        */
    }
}
