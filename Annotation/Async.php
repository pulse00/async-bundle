<?php

namespace Dubture\AsyncBundle\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Async
{
    /** @var string */
    public $service;

    /** @var string */
    public $routingKey;

}