<?php

return [
    /*
     * This value will be sent along with your trace.
     */
    'default_trace_name' => 'My package',

    /*
     * A driver is responsible for transmitting any measurements.
     */
    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'http://localhost:9412/api/v2/spans',
        ],
    ],

    /*
     * Tags can be added to any measurement. These classes will determine the
     * values of the tags when a new trace starts.
     */
    'trace_tag_providers' => [
        \Spatie\OpenTelemetry\Support\TagProviders\DefaultTagsProvider::class,
    ],

    /*
     * Tags can be added to any measurement. These classes will determine the
     * values of the tags when a new span starts.
     */
    'span_tag_providers' => [

    ],

    'queue' => [
        /*
         * When enabled, any measurements you make in a queued job that implement
         * TraceAware will belong to the same trace that was started in
         * the process that dispatched the job.
         */
        'make_queue_trace_aware' => true,

        /*
         * When this is set to false, only jobs the implement
         * TraceAware will be trace aware.
         */
        'all_jobs_are_trace_aware_by_default' => true,

        /*
         * The jobs we be trace aware even if these don't
         * implement the TraceAware interface.
         */
        'trace_aware_jobs' => [

        ],

        /*
         * The jobs will never trace aware.
         */
        'not_trace_aware_jobs' => [

        ],
    ],

    /*
     * These actions can be overridden to have fine-grained control over how
     * the package performs certain tasks.
     *
     * In most cases, you should use the defaults.
     */
    'actions' => [
        'make_queue_trace_aware' => Spatie\OpenTelemetry\Actions\MakeQueueTraceAwareAction::class,
    ],

    /*
     * This class determines how the package measures time.
     */
    'stop_watch' => Spatie\OpenTelemetry\Support\StopWatch::class,

    /*
     * This class generates ids for both traces and spans.
     */
    'id_generator' => Spatie\OpenTelemetry\Support\IdGenerator::class,
];
