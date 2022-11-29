<?php

namespace Spatie\OpenTelemetry\Support;

use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class Span
{
    protected StopWatch $stopWatch;

    protected string $id;

    /**
     * @var array<string, mixed>
     */
    protected array $tags;

    /**
     * @param  string  $name
     * @param  \Spatie\OpenTelemetry\Support\Trace  $trace
     * @param  array<\Spatie\OpenTelemetry\Support\TagProviders\TagProvider>  $tagProviders
     * @param  \Spatie\OpenTelemetry\Support\Span|null  $parentSpan
     */
    public function __construct(
        protected string $name,
        protected Trace $trace,
        protected array $tagProviders,
        protected ?Span $parentSpan = null,
    ) {
        $this->stopWatch = app(StopWatch::class)->start();

        $this->id ??= app(IdGenerator::class)->spanId();

        $this->tags = collect($this->tagProviders)
            ->map(fn (string $tagProvider) => app($tagProvider))
            ->flatMap(fn (TagProvider $tagProvider) => $tagProvider->tags())
            ->toArray();
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

    public function tags(array $tags): self
    {
        $this->tags = array_merge($this->tags, $tags);

        return $this;
    }

    public function getTags(): array
    {
        return array_merge(
            $this->trace->getTags(),
            $this->tags,
        );
    }

    public function toTraceContextID(): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            '00',         // version
            $this->trace->id(),    // trace id
            $this->id,             // span id
            '01'                   // flags - https://www.w3.org/TR/trace-context/#trace-flags; 01 - Should be traced in next application
        );
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
            'tags' => $this->getTags(),
            'parentId' => $this->parentSpan?->id(),
            'otel.scope.name' => $this->trace->getName(),
        ];
    }
}
