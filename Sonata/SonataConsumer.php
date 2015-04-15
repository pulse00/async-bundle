<?php

namespace Dubture\AsyncBundle\Sonata;

use Sonata\NotificationBundle\Consumer\ConsumerInterface;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SonataConsumer
 * @package Dubture\Async\Sonata
 */
class SonataConsumer implements ConsumerInterface
{
    /** @var ContainerInterface  */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConsumerEvent $event)
    {
        $body = $event->getMessage()->getBody();
        $service = $body['service'];
        $method = $body['method'];
        $args = $body['arguments'];

        $this->container->get('dubture.async.interceptor')->executeInvocation($service, $method, $args);
    }
}