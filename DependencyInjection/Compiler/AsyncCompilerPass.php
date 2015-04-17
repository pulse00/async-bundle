<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\DependencyInjection\Compiler;

use CG\Core\ReflectionUtils;
use Dubture\AsyncBundle\Annotation\Async;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AsyncCompilerPass
 * @package Dubture\AsyncBundle\DependencyInjection\Compiler
 */
class AsyncCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('dubture_async');
        $interceptor = $container->findDefinition('dubture.async.interceptor');
        $backend = $configs[0]['backend'];
        $interceptor->replaceArgument(1, new Reference('dubture.async.backend.' . $backend));

        if ($backend === 'sonata') {
            $this->setupSonataConsumers($container);
        }
    }

    /**
     * Creates the necessary consumers for the sonata backend based on the @Async annotations
     *
     * @param ContainerBuilder $container
     */
    private function setupSonataConsumers(ContainerBuilder $container)
    {
        $pointcut = $container->get('dubture_async_bundle.pointcut.asyncpointcut');
        $sonataConsumers = array();

        // search all service definitions for Async annotations
        foreach ($container->getDefinitions() as $definition) {

            $className = $definition->getClass();

            if ($className === null || strpos($className, '%') !== false) {
                continue;
            }

            $class = new \ReflectionClass($className);

            foreach (ReflectionUtils::getOverrideableMethods($class) as $method) {

                // we found an Async annotation, create the sonata consumer for it
                if ($pointcut->matchesMethod($method)) {

                    $reader = $container->get('annotations.reader');
                    /** @var Async $annotation */
                    $annotation = $reader->getMethodAnnotation($method, 'Dubture\AsyncBundle\Annotation\Async');
                    $routingKey = $annotation->routingKey !== null ? $annotation->routingKey : 'default';

                    if (array_key_exists($routingKey, $sonataConsumers)) {
                        continue;
                    }

                    $definition = $container->register('dubture.async.sonata_' . $routingKey, 'Dubture\AsyncBundle\Sonata\SonataConsumer');
                    $definition->addArgument(new Reference('dubture.async.executor'));
                    $definition->addTag('sonata.notification.consumer', array('type' => $routingKey));
                    $sonataConsumers[$routingKey] = $definition;
                }
            }
        }
    }
}