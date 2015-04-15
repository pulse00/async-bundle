<?php

namespace Dubture\AsyncBundle\RabbitMQ;
use Dubture\AsyncBundle\Backend\AsyncBackend;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RabbitMQBackend
 * @package Dubture\AsyncBundle\RabbitMQ
 */
class RabbitMQBackend implements AsyncBackend
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
    public function publishInvocation($service, $method, array $arguments, array $options = array())
    {
        $producerName = 'old_sound_rabbit_mq.' . $options['routingKey'] . '_producer';

        /** @var Producer $producer */
        $producer = $this->container->get($producerName);

        $msg = array(
            'service' => $service,
            'method' => $method,
            'arguments' => $arguments
        );

        $producer->publish(serialize($msg));
    }
}