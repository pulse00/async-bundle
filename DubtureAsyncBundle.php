<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle;

use Dubture\AsyncBundle\DependencyInjection\Compiler\AsyncCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DubtureAsyncBundle
 * @package Dubture\AsyncBundle\Bundle
 */
class DubtureAsyncBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AsyncCompilerPass());
    }
}
