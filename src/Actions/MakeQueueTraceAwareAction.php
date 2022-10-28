<?php

namespace Spatie\OpenTelemetry\Actions;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobRetryRequested;
use ReflectionClass;
use Spatie\OpenTelemetry\Facades\Measure;
use Spatie\OpenTelemetry\Jobs\NotTraceAware;
use Spatie\OpenTelemetry\Jobs\TraceAware;

class MakeQueueTraceAwareAction
{
    public function execute(): void
    {
        $this
            ->listenForJobsBeingQueued()
            ->listenForJobsBeingProcessed()
            ->listenForProcessedJobs()
            ->listenForJobsRetryRequested();
    }

    protected function listenForJobsBeingQueued(): self
    {
        app('queue')->createPayloadUsing(function ($connectionName, $queue, $payload) {
            $queueable = $payload['data']['command'];

            if (! $this->isTraceAware($queueable)) {
                return [];
            }

            $currentTraceId = Measure::traceId();

            if ($currentTraceId) {
                return ['traceId' => $currentTraceId];
            }
        });

        return $this;
    }

    protected function listenForJobsBeingProcessed(): self
    {
        app('events')->listen(JobProcessing::class, function (JobProcessing $event) {
            if (! array_key_exists('traceId', $event->job->payload())) {
                return;
            }

            $traceId = $event->job->payload()['traceId'];

            Measure::trace()?->setId($traceId);

            if (config('open-telemetry.queue.all_jobs_auto_start_a_span')) {
                $this->startSpanForJob($event->job);
            }
        });

        return $this;
    }

    public function listenForProcessedJobs(): self
    {
        app('events')->listen(JobProcessed::class, function (JobProcessed $event) {
            if (! config('open-telemetry.queue.all_jobs_auto_start_a_span')) {
                return;
            }

            $jobName = $this->jobName($event->job);

            Measure::stop($jobName);
        });

        return $this;
    }

    protected function listenForJobsRetryRequested(): self
    {
        app('events')->listen(JobRetryRequested::class, function (JobRetryRequested $event) {
            if (! array_key_exists('traceId', $event->payload())) {
                return;
            }

            $traceId = $event->payload()['traceId'];

            Measure::trace()?->setId($traceId);
        });

        return $this;
    }

    protected function isTraceAware(object $queueable): bool
    {
        $reflection = new ReflectionClass($queueable);

        if ($reflection->implementsInterface(TraceAware::class)) {
            return true;
        }

        if ($reflection->implementsInterface(NotTraceAware::class)) {
            return false;
        }

        if (in_array($reflection->name, config('open-telemetry.queue.trace_aware_jobs'))) {
            return true;
        }

        if (in_array($reflection->name, config('open-telemetry.queue.not_trace_aware_jobs'))) {
            return false;
        }

        return config('open-telemetry.queue.all_jobs_are_trace_aware_by_default') === true;
    }

    protected function getEventPayload($event): ?array
    {
        return match (true) {
            $event instanceof JobProcessing => $event->job->payload(),
            $event instanceof JobRetryRequested => $event->payload(),
            default => null,
        };
    }

    protected function startSpanForJob(Job $job)
    {
        $jobName = $this->jobName($job);

        Measure::start($jobName);
    }

    protected function jobName(object $job): string
    {
        return $job->payload()['displayName'];
    }
}
