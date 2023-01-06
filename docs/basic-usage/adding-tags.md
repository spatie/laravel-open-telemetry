---
title: Adding tags
weight: 4
---

In Open Telemetry, traces and spans can have tags attached. These can be useful to track a system's hostname, internal IP address, or other metadata.

## Manually adding tags

Using our package, you can add tags to traces, by chaining on the `tags()` method when starting to measure something. These tags will be merged with automatically added tags.

```php
use \Spatie\OpenTelemetry\Facades\Measure;

Measure::start('span name')->tags([
    'tag name' => 'tag value',
])
```

## Automatically adding tags

This package can automatically add tags to any measurement made. This is done using the tag providers configured in `config/open-telemetry.php`. A tag providers is any class the implements the `Spatie\OpenTelemetry\Support\TagProviders\TagProvider` interface.

Here's an example:

```php
use Spatie\OpenTelemetry\Support\TagProviders\TagProvider;

class HostNameTagProvider implements TagProvider
{
    public function tags(): array
    {
        return [
            'host.name' => gethostname(),
        ];
    }
}
```

This tag provider can be resolved when a trace starts by adding it the `trace_tag_providers` key in the `config/open-telemetry.php` config file. If that tag provider should be executed before each span (so each time you can `Measure::start()`), you should add it to the `span_tag_providers` key in the config file.
