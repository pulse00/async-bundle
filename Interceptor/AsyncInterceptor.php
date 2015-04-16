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

        /** @var MetadataFactory $factory */
        $factory = $this->container->get('jms_di_extra.metadata.metadata_factory');

        /** @var ClassHierarchyMetadata $metadata */
        $metadata = $factory->getMetadataForClass($invocation->reflection->getDeclaringClass()->getName());

        /** @var Async $async */
        $async = $this->reader->getMethodAnnotation($invocation->reflection, 'Dubture\AsyncBundle\Annotation\Async');

        $options = array(
            'routingKey' => $async->routingKey
        );

        $this->backend->publishInvocation(
            $metadata->getOutsideClassMetadata()->id,
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