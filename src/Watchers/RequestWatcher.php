<?php

namespace Spatie\OpenTelemetry\Watchers;

use Illuminate\Foundation\Application;
use Spatie\OpenTelemetry\Support\Measure;

class RequestWatcher extends Watcher
{
    public function register(Application $app)
    {
        Measure::start('request'); //TODO start time mergen

        $app->terminating(function () {
            Measure::stop('request');
        });
    }
}
