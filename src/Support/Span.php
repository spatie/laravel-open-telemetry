<?php

namespace Spatie\OpenTelemetry\Support;

use OpenTelemetry\SDK\Common\Time\StopWatchFactory;
use OpenTelemetry\SDK\Trace\RandomIdGenerator;

class Span
{
    protected Stopwatch $stopWatch;

    protected string $id;

    public string $name;

    public function __construct(string $name, protected Trace $trace, protected ?Span $parentSpan = null)
    {
        $this->name = $name;

        $this->stopWatch = (new Stopwatch())->start();

        $this->id ??= (new RandomIdGenerator())->generateSpanId();
    }

    public function id(): int
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
            'timestamp' => $this->stopWatch->startTime(),
            'duration' => $this->stopWatch->elapsedTime(),
            'tags' => [],
            'parentId' => $this->parentSpan?->id(),
            'otel.scope.name' => 'to do fill in',
        ];
    }
}
