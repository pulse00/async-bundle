<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Interceptor;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AsyncExecutor
 * @package Dubture\Async\Interceptor
 */
class AsyncExecutor
{
    /** @var ContainerInterface  */
    private $container;

    /** @var LoggerInterface  */
    private $logger;

    /**
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * @param $service
     * @param $method
     * @param array $arguments
     */
    public function executeInvocation($service, $method, array $arguments)
    {
        $service = $this->container->get($service);
        $service->{AsyncInterceptor::HINT} = true;

        $this->logger->debug('Async backend invoked service call ' . get_class($service) . '::' . $method);
        call_user_func_array(array($service, $method), $arguments);
    }
}