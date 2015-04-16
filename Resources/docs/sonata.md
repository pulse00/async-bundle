## Sonata Backend

### Usage

Annotate your worker services:


```php

use Dubture\AsyncBundle\Annotation\Async;

class MediaTranscodingService
{
    /**
     * @Async(routingKey="upload_picture")
     */
    public function transcodeFile($sourcePath)
    {
            // ... do some heavy-lifting, e.g. media transcoding
    }
}
```


```php

use Dubture\AsyncBundle\Annotation\Async;

class BulkEmailService
{
    /**
     * @Async
     */
    public function sendBulkEmail(array $emails))
    {
        // ... send bulk email
    }
}
```

Configure the sonata workers:


```yml

sonata_notification:
    backend: sonata.notification.backend.rabbitmq
    queues:
        - { queue: transcoder, routing_key: upload_picture }
        - { queue: catchall, default: true }

    backends:
        rabbitmq:
            exchange:     router
            connection:
                host:     localhost
                user:     guest
                pass:     guest
                port:     5672
                vhost:    '/'

```

Then run your workers:


Will handle all `@Async` methods without a `routingKey` attribute:

`./app/console sonata:notification:start`


Will handle only `@Async` methods the `upload_picture` attribute:

`./app/console sonata:notification:start --type=upload_picture`


For more details on how to run/maintain workers, see the [`https://github.com/sonata-project/SonataNotificationBundle`](SonataNotificationBundle) documentation.