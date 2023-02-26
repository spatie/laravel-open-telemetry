<?php

namespace Spatie\OpenTelemetry\Support;

use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class Span
{
    protected Stopwatch $stopWatch;

    protected string $id;

    protected int $flags;

    /** @var array<string, mixed> */
    protected array $tags;

    /** @var array<string, mixed> */
    public array $mergeProperties;

    /**
     * @param  \Spatie\OpenTelemetry\Support\Trace  $trace
     * @param  array<\Spatie\OpenTelemetry\Support\TagProviders\TagProvider>  $tagProviders
     * @param  \Spatie\OpenTelemetry\Support\Span|null  $parentSpan
     */
    public function __construct(
        protected string $name,
        protected Trace $trace,
        protected array $tagProviders,
        protected ?Span $parentSpan = null,
        array $mergeProperties = [],

    ) {
        $this->stopWatch = app(Stopwatch::class)->start();

        $this->id ??= app(IdGenerator::class)->spanId();

        $this->tags = collect($this->tagProviders)
            ->map(fn (string $tagProvider) => app($tagProvider))
            ->flatMap(fn (TagProvider $tagProvider) => $tagProvider->tags())
            ->toArray();

        $this->mergeProperties = $mergeProperties;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function parentSpan(): ?Span
    {
        return $this->parentSpan;
    }

    public function trace(): ?Trace
    {
        return $this->trace;
    }

    public function flags(): int
    {
        return 0x01;
    }

    public function stop(array $mergeProperties = []): self
    {
        $this->stopWatch->stop();

        $this->mergeProperties = array_merge($this->mergeProperties, $mergeProperties);

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

    public function toArray(): array
    {
        return array_merge([
            'id' => $this->id,
            'traceId' => $this->trace->id(),
            'localEndpoint' => [
                'serviceName' => $this->trace->getName(),
            ],
            'name' => $this->name,
            'timestamp' => $this->stopWatch->startTime(),
            'duration' => $this->stopWatch->elapsedTime(),
            'tags' => $this->getTags(),
            'parentId' => $this->parentSpan?->id(),
            'otel.scope.name' => $this->trace->getName(),
        ], $this->mergeProperties);
    }
}
