<?php

namespace Dubture\AsyncBundle\Resque;

use BCC\ResqueBundle\Resque;
use Dubture\AsyncBundle\Backend\AsyncBackend;

/**
 * Class ResqueBackend
 * @package Dubture\AsyncBundle\Backend
 */
class ResqueBackend implements AsyncBackend
{
    /** @var Resque  */
    private $resque;

    /**
     * @param Resque $resque
     */
    public function __construct(Resque $resque)
    {
        $this->resque = $resque;
    }

    /**
     * {@inheritdoc}
     */
    public function publishInvocation($service, $method, array $arguments, array $options = null)
    {
        $job = new ResqueJob();
        $job->args = array(
            'service' => $service,
            'method' => $method,
            'arguments' => $arguments
        );

        if ($options['routingKey'] !== null) {
            $job->queue = $options['routingKey'];
        }

        $this->resque->enqueue($job);
    }
}