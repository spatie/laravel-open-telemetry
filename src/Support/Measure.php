<?php

namespace Spatie\OpenTelemetry\Support;

use Spatie\OpenTelemetry\Drivers\Driver;

class Measure
{
    protected Driver $driver;

    protected ?Trace $trace = null;

    protected ?Span $parentSpan = null;

    /** @var array<string, \Spatie\OpenTelemetry\Support\Span> */
    protected array $startedSpans = [];

    protected bool $shouldSample = true;

    public function __construct(Driver $driver, bool $shouldSample = true)
    {
        $this->startTrace();

        $this->driver = $driver;

        $this->shouldSample = $shouldSample;
    }

    public function startTrace(): self
    {
        if (! $this->shouldSample) {
            return $this;
        }

        $traceName = config('open-telemetry.default_trace_name') ?? config('app.name');

        $this->trace = Trace::start(name: $traceName);

        return $this;
    }

    public function traceId(): ?string
    {
        return $this->trace?->id();
    }

    public function hasTraceId(): bool
    {
        $traceId = $this->traceId();

        if (is_null($traceId)) {
            return false;
        }

        return $traceId !== '0';
    }

    public function setTraceId(string $traceId)
    {
        if (! $this->trace) {
            return $this;
        }

        $this->trace->setId($traceId);

        return $this;
    }

    public function setDriver(Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function trace(): ?Trace
    {
        return $this->trace;
    }

    public function start(string $name, array $mergeProperties = []): ?Span
    {
        if (! $this->shouldSample) {
            return null;
        }

        $span = new Span(
            $name,
            $this->trace,
            config('open-telemetry.span_tag_providers'),
            $this->parentSpan,
            $mergeProperties,
        );

        $this->startedSpans[$name] = $span;

        $this->parentSpan = $span;

        return $span;
    }

    public function getSpan(string $name): ?Span
    {
        return $this->startedSpans[$name] ?? null;
    }

    public function currentSpan(): ?Span
    {
        return $this->parentSpan;
    }

    public function startedSpanNames(): array
    {
        return array_keys($this->startedSpans);
    }

    public function stop(string $name, array $mergeProperties = []): ?Span
    {
        if (! $this->shouldSample) {
            return null;
        }

        $span = $this->startedSpans[$name] ?? null;

        if (! $span) {
            return null;
        }

        $span->stop($mergeProperties);

        unset($this->startedSpans[$name]);
        $this->parentSpan = $span->parentSpan();

        $this->driver->sendSpan($span);

        return $span;
    }

    public function manual(string $name, float $durationInMs, array $mergeProperties = [])
    {
        $this->start($name);

        $endTime = now()->getPreciseTimestamp();

        $durationInMicroseconds = $durationInMs * 1000;

        $startTime = $endTime - $durationInMicroseconds;

        $mergeProperties = array_merge([
            'timestamp' => $startTime,
            'duration' => $durationInMicroseconds,
        ], $mergeProperties);

        $this->stop($name, $mergeProperties);
    }
}
