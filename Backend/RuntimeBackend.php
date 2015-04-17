<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Backend;

use Dubture\AsyncBundle\Interceptor\AsyncExecutor;

/**
 * Class RuntimeBackend
 * @package Dubture\AsyncBundle\Backend
 */
class RuntimeBackend implements AsyncBackend
{
    /** @var AsyncExecutor */
    private $executor;

    /**
     * @param AsyncExecutor $executor
     */
    public function __construct(AsyncExecutor $executor)
    {
        $this->executor = $executor;
    }

    /**
     * {@inheritdoc}
     */
    public function publishInvocation($service, $method, array $arguments, array $options = null)
    {
        $this->executor->executeInvocation($service, $method, $arguments);
    }
}