<?php

namespace Spatie\OpenTelemetry\Support;

use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class Trace
{
    /** @var array<string, mixed> */
    protected array $tags = [];

    public static function start(string $id = null, string $name = ''): self
    {
        return new self($id, $name, config('open-telemetry.trace_tag_providers'));
    }

    /**
     * @param  array<\Spatie\OpenTelemetry\Support\TagProviders\TagProvider>  $tagProviders
     */
    public function __construct(
        protected ?string $id,
        protected ?string $name,
        array $tagProviders,
    ) {
        $this->id ??= app(IdGenerator::class)->traceId();

        $this->tags = collect($tagProviders)
            ->map(fn (string $tagProvider) => app($tagProvider))
            ->flatMap(fn (TagProvider $tagProvider) => $tagProvider->tags())
            ->toArray();
    }

    public function setId(string $traceId)
    {
        $this->id = $traceId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTags(): array
    {
        return $this->tags ?? [];
    }
}
