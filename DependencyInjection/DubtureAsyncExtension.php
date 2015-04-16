<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class DubtureAsyncExtension
 * @package Dubture\AsyncBundle\DependencyInjection
 */
class DubtureAsyncExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $validBackends = array('rabbitmq', 'resque', 'runtime', 'sonata');
        $backend = $config['backend'];

        if (!in_array($backend, $validBackends)) {
            $message = sprintf('The async backend "%s" is invalid. Valid backends are: %s',
                    $backend, implode(',', $validBackends));
            throw new InvalidConfigurationException($message);
        }

        $loader->load('backend' . DIRECTORY_SEPARATOR . $backend . '.xml');
    }
}
