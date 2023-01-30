<?php

namespace Spatie\OpenTelemetry\Watchers;

use Illuminate\Foundation\Application;

abstract class Watcher
{
    abstract public function register(Application $app);
}
