---
title: Measuring queued jobs
weight: 3
---

Whenever a request starts, the package will automatically generate a trace id. This trace id will be used in a spans you start with `Measure::start($name)`. 

When that request dispatched a queued job, the package will make sure that any spans you start within that queued job will use the same trace id. 

Because the spans within a request and the queued jobs it dispatch is the same, reporting tools like Jaeger are able to combine them.

As a package user, you don't have to think about this at all. Just perform measurements with `Measure::start()` and the package will take care the expected traces and spans are sent.


