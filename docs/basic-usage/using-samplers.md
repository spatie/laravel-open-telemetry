---
title: Using samplers
weight: 5
---

By default, the package will start traces in every request that goes to your Laravel app. In production, you'll likely don't want this as it might hurt performance a little.

Instead of measure every request, you can measure only a small portion of requests using a sampler. A sampler is a class that determines which requests should be measured. By default, the package uses the `Spatie\OpenTelemetry\Support\Samplers\AlwaysSampler` samples. This can be configured in the `sampler` key of the `open-telemetry.php` config file.

If you don't want to measure every request, you can use the `Spatie\OpenTelemetry\Support\Samplers\LotterySampler` that ships with the package. This sampler will measure performance for 2 out of 100 requests. To use this sampler, simply specify its class name the `sampler` key of the `open-telemetry.php` config file.

## Creating your own sampler

A sampler is any class that extends `Spatie\OpenTelemetry\Support\Samplers`. This abstract class requires you to implement a method `shouldSample` that should return a boolean. Here's an example where we create a `CustomLotterySampler` that will measure performance for 5 out of 1000 requests.

```php
namespace App\Support\Samplers;

use Illuminate\Support\Lottery;

class LotterySampler extends Sampler
{
    public function shouldSample(): bool
    {
        return Lottery::odds(5, 1000)->choose();
    }
}
```

After creating your sampler, don't forget to put it's class name in the `sampler` key of the `open-telemetry.php` config file.
