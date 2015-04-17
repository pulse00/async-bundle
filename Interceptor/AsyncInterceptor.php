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
use Metadata\ClassHierarchyMetadata;
use Metadata\MetadataFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AsyncInterceptor
 * @package Dubture\AsyncBundle\Interceptor
 */
class AsyncInterceptor implements MethodInterceptorInterface
{
    /** property being set on the invokable so we know when to invoke the real */
    const HINT = '__dubture_async_hint';

    /** @var Reader */
    private $reader;

    /** @var AsyncBackend  */
    private $backend;

    /** @var MetadataFactory  */
    private $factory;

    /** @var LoggerInterface  */
    private $logger;

    /**
     * @param Reader $reader
     * @param AsyncBackend $backend
     * @param MetadataFactory $factory
     * @param LoggerInterface $logger
     */
    public function __construct(Reader $reader, AsyncBackend $backend,
                                MetadataFactory $factory, LoggerInterface $logger)
    {
        $this->reader = $reader;
        $this->backend = $backend;
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

        if (property_exists($invocation->object, self::HINT)) {
            $this->logger->debug('Invoking service method from async backend: ' . $className . '::'. $methodName);
            $invocation->proceed();
            return;
        }

        /** @var ClassHierarchyMetadata $metadata */
        $metadata = $this->factory->getMetadataForClass($className);

        if ($metadata === null) {
            throw new \RuntimeException("Unable to load class metadata for " . $className);
        }

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
}