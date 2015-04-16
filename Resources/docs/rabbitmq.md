## RabbitMQ Backend

### Configuration

The configuration is solely based on the config provided by the [`RabbitMqBundle`](https://github.com/videlalvaro/RabbitMqBundle).

First, you need to define the available queues:


```yml
#app/config/config.yml

old_sound_rabbit_mq:
    # ...
    producers:
        upload_picture:
            connection:       default
            exchange_options: {name: 'upload-picture', type: direct}
        catch_all:
            connection:       default
            exchange_options: {name: 'catch-all', type: direct}
    consumers:
        upload_picture:
            connection:       default
            exchange_options: {name: 'upload-picture', type: direct}
            queue_options:    {name: 'upload-picture'}
            callback:         dubture.async.rabbitconsumer
        catch_all:
            connection:       default
            exchange_options: {name: 'upload-picture', type: direct}
            queue_options:    {name: 'upload-picture'}
            callback:         dubture.async.rabbitconsumer
```

Second, annotate your worker services:

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
     * @Async(routingKey="catch_all")
     */
    public function sendBulkEmail(array $emails))
    {
        // ... send bulk email
    }
}
```

And finally, run 2 workers:


`./app/console rabbitmq:consumer -m 50 catch_all`

`./app/console rabbitmq:consumer -m 50 upload_picture`


Done. Any calls to the above methods annotated with `@Async` will be processed in your
backend workers.


For more details on how to run/maintain workers, see the `RabbitMqBundle` documentation.