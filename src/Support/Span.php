<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\SDK\Common\Time\StopWatchFactory;
use OpenTelemetry\SDK\Trace\RandomIdGenerator;

class Span
{
    protected StopWatch $stopWatch;

    protected string $id;

    public string $name;

    public function __construct(
        string $name, protected
        Trace $trace, protected
        ?Span $parentSpan = null)
    {
        $this->name = $name;

        $this->stopWatch = app(StopWatch::class)->start();

        $this->id ??= app(IdGenerator::class)->spanId();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function parentSpan(): ?Span
    {
        return $this->parentSpan;
    }

    public function stop(): self
    {
        $this->stopWatch->stop();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'traceId' => $this->trace->id(),
            'localEndpoint' => [
                'serviceName' => $this->trace->getName(),
            ],
            'name' => $this->name,
            'timestamp' => intdiv($this->stopWatch->startTime(), 1000),
            'duration' => $this->stopWatch->elapsedTime(),
            'tags' => [],
            'parentId' => $this->parentSpan?->id(),
            'otel.scope.name' => $this->trace->getName(),
        ];
    }
}
