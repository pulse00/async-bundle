<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Resque;

use BCC\ResqueBundle\ContainerAwareJob;
use Dubture\AsyncBundle\Backend\RuntimeBackend;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ResqueJob
 * @package Dubture\AsyncBundle\Resque
 */
class ResqueJob extends ContainerAwareJob
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function run($args)
    {
        $interceptor = $this->getContainer()->get('dubture.async.interceptor');
        $interceptor->executeInvocation($args['service'], $args['method'], $args['arguments']);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
      return $this->container;
    }

    /**
     * @param $container
     */
    public function setContainer(ContainerInterface $container)
    {
      $this->container = $container;
    }
}
