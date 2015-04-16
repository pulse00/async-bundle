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

/**
 * Class AsyncBackend
 * @package Dubture\AsyncBundle\Pointcut
 */
interface AsyncBackend
{

    /**
     * @param $service
     * @param $method
     * @param array $arguments
     * @param array $options
     * @return mixed
     */
    function publishInvocation($service, $method, array $arguments, array $options = null);
}