# Async Bundle

[![Build Status](https://travis-ci.org/pulse00/async-bundle.svg?branch=master)](https://travis-ci.org/pulse00/async-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pulse00/async-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pulse00/async-bundle/?branch=master)

This Symfony bundle provides a high-level way of sending expensive logic to background workers.

## Configuration

1. Install the bundle using `composer require "dubture/async-bundle"`
2. Add the necessary bundles to your Kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(

        // register the async bundle
        new Dubture\AsyncBundle\DubtureAsyncBundle(),

        // register the dependencies of the async bundle
        new JMS\DiExtraBundle\JMSDiExtraBundle($this),
        new JMS\AopBundle\JMSAopBundle(),

        // your application bundles here...

    );

    return $bundles;
}
```

3. Configure which backend to use:


```yml
# app/config/config.yml
dubture_async:
  backend: rabbitmq # one of rabbitmq|resque|sonata|runtime
```


## Usage

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

If you want to delegate this method to a background worker, this is all you need to do:

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

Now any call to `transcodeFile` will be intercepted and delegated to a background worker.


Methods annotated with `@Async` need to adhere to the following contract:

1. The class declaring the async method must be a service
2. The methods arguments must be serializable (no resources, e.g. doctrine connections)
3. The method should not return anything (any return value will be lost)

If you need to react to something happening inside your background worker, you can simply dispatch
events when it's done.

The background-worker implementation relies on one of the following bundles:

- https://github.com/michelsalib/BCCResqueBundle (resque)
- https://github.com/videlalvaro/RabbitMqBundle (rabbitmq)
- https://github.com/sonata-project/SonataNotificationBundle (see sonata bundle for available backends)


See `Resources/docs` for documentation of the specific backends.

