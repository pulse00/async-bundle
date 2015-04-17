<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Sonata;

use Dubture\AsyncBundle\Interceptor\AsyncExecutor;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SonataConsumer
 * @package Dubture\Async\Sonata
 */
class SonataConsumer implements ConsumerInterface
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
    public function process(ConsumerEvent $event)
    {
        $body = $event->getMessage()->getBody();
        $this->executor->executeInvocation($body['service'], $body['method'], $body['arguments']);
    }
}