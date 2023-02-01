<?php

namespace Spatie\OpenTelemetry\Watchers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Event;

class HttpClientWatcher extends Watcher
{
    public function register(Application $app)
    {
        Event::listen(RequestSending::class, function (RequestSending $event) {
            // to do implement
        });

        Event::listen(ResponseReceived::class, function (ResponseReceived $event) {
            // to do implement
        });
    }
}
