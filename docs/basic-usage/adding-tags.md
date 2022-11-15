---
title: Adding tags
weight: 4
---

In Open Telemetry a span can have one are many tags.

## Manually add tags

Using our package, you can simply add tags, by chaining on `tags()` when starting to measure something. These tags will be merged with automatically added tags.

```php
use \Spatie\OpenTelemetry\Facades\Measure;

Measure::start('span name')->tags([
    'tag name' => 'tag value',
])
```

## Automatically added tags

This package can automatically add tags to any measurement made. This is done by tag providers. A tag providers is any class the implements the `Spatie\OpenTelemetry\Support\TagProviders\TagProvider` interface. 

Here's an example:

```php
use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class MyTagProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'host.name' => gethostname(),
        ];
    }
}
```

This tag provider can be resolved when a trace starts by adding it the `trace_tag_providers` key in the `open-telemetry` config file. If that tag provider should be executed before each span (so each time you can `Measure::start()`), you should add it to the `span_tag_providers` key in the config file.
