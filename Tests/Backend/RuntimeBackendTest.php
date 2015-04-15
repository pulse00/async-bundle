<?php

namespace Dubture\AsyncBundle\Tests\Backend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Dubture\AsyncBundle\Tests\Controller
 */
class RuntimeBackendTest extends WebTestCase
{
    public function testRuntimeBackend()
    {
        $client = static::createClient();
        $testService = $client->getKernel()->getContainer()->get('test_service');
        $testService->doWork('something');

        $this->assertEquals('something', $testService->getPayload());
    }
}
