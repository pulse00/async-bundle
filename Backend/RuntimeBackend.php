<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Backend;

use Dubture\AsyncBundle\Interceptor\AsyncInterceptor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RuntimeBackend
 * @package Dubture\AsyncBundle\Pointcut
 */
class RuntimeBackend implements AsyncBackend
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
    public function publishInvocation($service, $method, array $arguments, array $options = null)
    {
        $this->container->get('dubture.async.interceptor')->executeInvocation($service, $method, $arguments);
    }
}