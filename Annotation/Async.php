<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Async
{
    /** @var string */
    public $routingKey;

}