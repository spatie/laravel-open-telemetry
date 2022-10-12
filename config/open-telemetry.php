<?php

return [
    'service_name' => 'Laravel',

    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'https://localhost:3303',
        ],
    ],
];
