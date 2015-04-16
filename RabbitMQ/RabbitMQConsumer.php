<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\RabbitMQ;

use Dubture\AsyncBundle\Interceptor\AsyncInterceptor;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RabbitMQConsumer
 * @package Dubture\AsyncBundle\RabbitMQ
 */
class RabbitMQConsumer implements ConsumerInterface
{
    /**
     * @var ContainerInterface
     */
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
    public function execute(AMQPMessage $msg)
    {
        $body = unserialize($msg->body);
        $this->container->get('dubture.async.interceptor')->executeInvocation($body['service'], $body['method'], $body['arguments']);
    }
}