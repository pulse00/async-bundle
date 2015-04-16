<?php

/*
 * This file is part of the DubtureAsyncBundle package.
 *
 * (c) Robert Gruendler <robert@dubture.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\AsyncBundle\Tests\Backend;

$_SERVER['KERNEL_DIR'] = 'Tests/App';

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Dubture\AsyncBundle\Tests\Controller
 */
class ResqueBackendTest extends WebTestCase
{
    public function testRuntimeBackend()
    {
        $client = static::createClient(array('environment' => 'resque'));
        $backend = $client->getKernel()->getContainer()->get('dubture.async.backend.resque');
        $testService = $client->getKernel()->getContainer()->get('test_service');

        $testService->doWork('something');

        $this->assertNull($testService->getPayload());
    }
}
