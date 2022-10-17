<?php

return [
    'default_trace_name' => 'Laravel',

    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'https://localhost:3303',
        ],
    ],

    'stop_watch' => Spatie\OpenTelemetry\Support\StopWatch::class,

    'id_generator' => Spatie\OpenTelemetry\Support\IdGenerator::class,
];
