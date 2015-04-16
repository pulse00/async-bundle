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

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;

use Doctrine\Common\Annotations\Reader;
use Dubture\AsyncBundle\Annotation\Async;
use Dubture\AsyncBundle\Backend\AsyncBackend;
use Dubture\AsyncBundle\Backend\RuntimeBackend;
use Metadata\ClassHierarchyMetadata;
use Metadata\MetadataFactory;
use Psr\Log\LoggerInterface;
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

    /** @var MetadataFactory  */
    private $factory;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Reader $reader
     * @param AsyncBackend $backend
     * @param MetadataFactory $factory
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     */
    public function __construct(Reader $reader, AsyncBackend $backend,
                                MetadataFactory $factory, LoggerInterface $logger, ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->backend = $backend;
        $this->container = $container;
        $this->factory = $factory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function intercept(MethodInvocation $invocation)
    {
        $methodName = $invocation->reflection->getName();
        $className = $invocation->reflection->getDeclaringClass()->getName();

        if (property_exists($invocation->object, RuntimeBackend::HINT)) {
            $this->logger->debug('Invoking service method from async backend: ' . $className . '::'. $methodName);
            $invocation->proceed();
            return;
        }

        /** @var ClassHierarchyMetadata $metadata */
        $metadata = $this->factory->getMetadataForClass($invocation->reflection->getDeclaringClass()->getName());

        /** @var Async $async */
        $async = $this->reader->getMethodAnnotation($invocation->reflection, 'Dubture\AsyncBundle\Annotation\Async');

        $options = array(
            'routingKey' => $async->routingKey
        );

        $this->logger->debug('Publishing method invocation to backend ' . $className . '::' . $methodName);

        $this->backend->publishInvocation(
            $metadata->getOutsideClassMetadata()->id,
            $methodName,
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