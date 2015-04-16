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

/**
 * Class TestService
 * @package Dubture\Async\Tests\App
 */
class TestService
{
    private $payload;


    /**
     * @Async(service="test_service")
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