<?php

namespace Dubture\AsyncBundle\Resque;

use BCC\ResqueBundle\ContainerAwareJob;
use Dubture\AsyncBundle\Backend\RuntimeBackend;

/**
 * Class ResqueJob
 * @package Dubture\AsyncBundle\Resque
 */
class ResqueJob extends ContainerAwareJob
{
    /**
     * {@inheritdoc}
     */
    public function run($args)
    {
        $interceptor = $this->getContainer()->get('dubture.async.interceptor');
        $interceptor->executeInvocation($args['service'], $args['method'], $args['arguments']);
    }
}