<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dubture\AsyncBundle\Annotation\Async;

use JMS\DiExtraBundle\Annotation as DI;


/**
 * @DI\Service("test_service")
 *
 * Class TestService
 * @package Dubture\Async\Tests\App
 */
class TestService
{
    private $payload;

    /**
     * @Async
     */
    public function doWork($withWhat)
    {
        $this->payload = $withWhat;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}