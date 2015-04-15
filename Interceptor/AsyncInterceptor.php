<?php

namespace Dubture\AsyncBundle\Interceptor;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;

use Doctrine\Common\Annotations\Reader;
use Dubture\AsyncBundle\Annotation\Async;
use Dubture\AsyncBundle\Backend\AsyncBackend;
use Dubture\AsyncBundle\Backend\RuntimeBackend;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AsyncInterceptor
 * @package Dubture\AsyncBundle\Interceptor
 */
class AsyncInterceptor implements MethodInterceptorInterface
{
    /** @var Reader */
    private $reader;

    /** @var AsyncBackend  */
    private $backend;

    /** @var ContainerInterface  */
    private $container;

    /**
     * @param Reader $reader
     * @param AsyncBackend $backend
     * @param ContainerInterface $container
     */
    public function __construct(Reader $reader, AsyncBackend $backend, ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->backend = $backend;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function intercept(MethodInvocation $invocation)
    {
        if (property_exists($invocation->object, RuntimeBackend::HINT)) {
            $invocation->proceed();
            return;
        }

        /** @var Async $async */
        $async = $this->reader->getMethodAnnotation($invocation->reflection, 'Dubture\AsyncBundle\Annotation\Async');

        $options = array(
            'routingKey' => $async->routingKey
        );

        $this->backend->publishInvocation(
            $async->service,
            $invocation->reflection->getName(),
            $invocation->arguments,
            $options
        );
    }

    /**
     * @param $service
     * @param $method
     * @param array $arguments
     */
    public function executeInvocation($service, $method, array $arguments)
    {
        $service = $this->container->get($service);
        $service->{RuntimeBackend::HINT} = true;

        call_user_func_array(array($service, $method), $arguments);
    }
}