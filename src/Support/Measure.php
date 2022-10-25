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

    public function __construct(Driver $driver)
    {
        $this->trace = Trace::start(name: config('open-telemetry.default_trace_name'));

        $this->driver = $driver;
    }

    public function traceId(): ?string
    {
        if (! $this->trace) {
            return null;
        }

        return $this->trace->id();
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

    public function trace(): ?string
    {
        return $this->trace?->id();
    }

    public function start(string $name): Span
    {
        $span = new Span(
            $name,
            $this->trace,
            config('open-telemetry.span_tag_providers'),
            $this->parentSpan,
        );

        $this->startedSpans[$name] = $span;

        $this->parentSpan = $span;

        return $span;
    }

    public function stop(string $name): ?Span
    {
        $span = $this->startedSpans[$name] ?? null;

        if (! $span) {
            return null;
        }

        $span->stop();

        unset($this->startedSpans[$name]);
        $this->parentSpan = $span->parentSpan();

        $this->driver->sendSpan($span);

        return $span;
    }
}
