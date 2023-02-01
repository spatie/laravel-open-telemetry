<?php

namespace Spatie\OpenTelemetry\Watchers;

use Illuminate\Foundation\Application;
use Spatie\OpenTelemetry\Facades\Measure;

class RequestWatcher extends Watcher
{
    public function register(Application $app)
    {
        Measure::start('request');

        $app->terminating(function () {
            $start = LARAVEL_START * 1_000_000;

            $duration = now()->getPreciseTimestamp() - $start;

            Measure::stop('request', [
                'timestamp' => $start,
                'duration' => $duration,
            ]);
        });
    }
}
