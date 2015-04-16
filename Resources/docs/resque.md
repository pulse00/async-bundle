## Resque Backend

### Usage

Annotate your worker services:


```php

use Dubture\AsyncBundle\Annotation\Async;

class MediaTranscodingService
{
    /**
     * @Async(service="media_transcoder", routingKey="upload_picture")
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
     * @Async(service="bulk_email_service")
     */
    public function sendBulkEmail(array $emails))
    {
        // ... send bulk email
    }
}
```

Then run your workers:


Will handle all '@Async` methods without a `routingKey` attribute:

`app/console bcc:resque:worker-start default`


Will handle only '@Async` methods the `upload_picture` attribute:

`app/console bcc:resque:worker-start upload_picture`


For more details on how to run/maintain workers, see the [`https://github.com/michelsalib/BCCResqueBundle`](BCCResqueBundle) documentation.