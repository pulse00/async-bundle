<?php

namespace Dubture\AsyncBundle\Sonata;
use Dubture\AsyncBundle\Backend\AsyncBackend;
use Sonata\NotificationBundle\Backend\BackendInterface;

/**
 * Class SonataBackend
 * @package Dubture\Async\Sonata
 */
class SonataBackend implements AsyncBackend
{
    /** @var BackendInterface  */
    private $backend;

    /**
     * @param BackendInterface $backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }

    /**
     * {@inheritdoc}
     */
    public function publishInvocation($service, $method, array $arguments, array $options = null)
    {
        $this->backend->createAndPublish(
                $options['routingKey'],
                [
                    'service' => $service,
                    'method' => $method,
                    'arguments' => $arguments
                ]
        );
    }
}