<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Pointcut;

use Doctrine\Common\Annotations\Reader;
use JMS\AopBundle\Aop\PointcutInterface;

/**
 * Class AsyncPointcut
 * @package Dubture\AsyncBundle\Pointcut
 */
class AsyncPointcut implements PointcutInterface
{
    /** @var Reader  */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function matchesClass(\ReflectionClass $class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method)
    {
        return null !== $this->reader->getMethodAnnotation($method,
                'Dubture\AsyncBundle\Annotation\Async');
    }
}