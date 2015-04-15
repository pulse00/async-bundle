<?php

namespace Dubture\AsyncBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
    }
}