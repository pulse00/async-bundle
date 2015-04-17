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
    public function testResqueBackend()
    {
        $client = static::createClient(array('environment' => 'resque'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\Resque\ResqueBackend')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.resque', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->once())
            ->method('publishInvocation')
            ->with($this->equalTo('test_service'), 'doWork', array('something'));

        $testService->doWork('something');

        $this->assertNull($testService->getPayload());
    }

    public function testResqueJob()
    {
        $client = static::createClient(array('environment' => 'resque'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\Resque\ResqueBackend')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.resque', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->never())
            ->method('publishInvocation');

        $job = new \Dubture\AsyncBundle\Resque\ResqueJob();
        $job->setContainer($client->getKernel()->getContainer());

        $job->run(array('service' => 'test_service', 'method' => 'doWork', 'arguments' => array('something')));

        $this->assertEquals('something', $testService->getPayload());
    }

}
