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

use Dubture\AsyncBundle\Interceptor\AsyncExecutor;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RabbitMQConsumer
 * @package Dubture\AsyncBundle\RabbitMQ
 */
class RabbitMQConsumer implements ConsumerInterface
{
    /** @var AsyncExecutor  */
    private $executor;

    /**
     * @param AsyncExecutor $executor
     */
    public function __construct(AsyncExecutor $executor)
    {
        $this->executor = $executor;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        $body = unserialize($msg->body);
        $this->executor->executeInvocation($body['service'], $body['method'], $body['arguments']);
    }
}