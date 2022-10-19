<?php

return [
    'default_trace_name' => 'My package',

    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'http://localhost:9412/api/v2/spans',
        ],
    ],

    'trace_tag_providers' => [
        \Spatie\OpenTelemetry\Support\TagProviders\DefaultTagsProvider::class,
    ],

    'span_tag_providers' => [

    ],

    'stop_watch' => Spatie\OpenTelemetry\Support\StopWatch::class,

    'id_generator' => Spatie\OpenTelemetry\Support\IdGenerator::class,

    'queue' => [
        'make_queue_trace_aware' => true,

        'all_jobs_are_trace_aware_by_default' => true,

        'trace_aware_jobs' => [

        ],

        'not_trace_aware_jobs' => [

        ],
    ],

    'actions' => [
        'make_queue_trace_aware' => Spatie\OpenTelemetry\Actions\MakeQueueTraceAwareAction::class
    ]
];
