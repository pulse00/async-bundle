<?php

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