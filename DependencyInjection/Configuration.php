<?php

namespace Dubture\AsyncBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Dubture\AsyncBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dubture_async');

        $rootNode
            ->children()
                ->scalarNode('backend')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
