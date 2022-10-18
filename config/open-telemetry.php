<?php

return [
    'default_trace_name' => 'My package',

    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'http://localhost:9412/api/v2/spans',
        ],
    ],

    'stop_watch' => Spatie\OpenTelemetry\Support\StopWatch::class,

    'id_generator' => Spatie\OpenTelemetry\Support\IdGenerator::class,
];
