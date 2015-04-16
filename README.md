### Dubture-Async Bundle

[![Build Status](https://travis-ci.org/pulse00/async-bundle.svg?branch=master)](https://travis-ci.org/pulse00/async-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pulse00/async-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pulse00/async-bundle/?branch=master)

This symfony-bundle provides a high-level way of sending expensive logic to backend workers.

Consider the following service:

```xml
<services>
    <service class="Acme\Bundle\MediaTranscodingService" id="media_transcoder">
    </service>
</services>
```

```php

class MediaTranscodingService
{
    public function transcodeFile($sourcePath)
    {
            // ... do some heavy-lifting, e.g. media transcoding
    }
}
```

If you want to delegate this method to a backend worker, this is all you need to do:

```php

use Dubture\AsyncBundle\Annotation\Async;

class MediaTranscodingService
{
    /**
     * @Async
     */
    public function transcodeFile($sourcePath)
    {
            // ... do some heavy-lifting, e.g. media transcoding
    }
}
```

Now any call to `transcodeFile` will be intercepted and delegated to a backend worker.


Methods annotated with `@Async` need to adhere to the following contract:

1. The method arguments must be serializable (no resources, e.g. doctrine connections)
2. The method should not return anything (any return value will be lost)

If you need to react to something happening inside your backend worker, you can simply dispatch
events when it's done.

### Configuration

```yml

dubture_async:
  backend: rabbitmq # one of rabbitmq|resque|sonata|runtime

```

The backend-worker implementation relies on one of the following bundles:

- https://github.com/michelsalib/BCCResqueBundle (resque)
- https://github.com/videlalvaro/RabbitMqBundle (rabbitmq)
- https://github.com/sonata-project/SonataNotificationBundle (see sonata bundle for available backends)


See `Resources/docs` for documentation of the specific backends.


### TODO

- [ ] Improve the Sonata backend -> Currently only one routing key is possible
