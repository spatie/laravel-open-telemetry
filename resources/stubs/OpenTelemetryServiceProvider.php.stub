<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\OpenTelemetry\Watchers\HttpClientWatcher;
use Spatie\OpenTelemetry\Watchers\QueryWatcher;
use Spatie\OpenTelemetry\Watchers\RequestWatcher;
use Spatie\OpenTelemetry\Watchers\Watcher;

class OpenTelemetryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerWatchers();
    }

    protected function registerWatchers(): self
    {
        collect([
            HttpClientWatcher::class,
            QueryWatcher::class,
            RequestWatcher::class,
        ])
            ->map(fn(string $watcherClass) => app($watcherClass))
            ->each(fn(Watcher $watcher) => $watcher->register(($this->app)));

        return $this;
    }
}
