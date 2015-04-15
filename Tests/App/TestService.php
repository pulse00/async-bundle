<?php

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