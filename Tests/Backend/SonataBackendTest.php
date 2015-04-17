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

use Dubture\AsyncBundle\Sonata\SonataConsumer;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Sonata\NotificationBundle\Model\Message;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Dubture\AsyncBundle\Tests\Controller
 */
class SonataBackendTest extends WebTestCase
{
    public function testSonataBackend()
    {
        $client = static::createClient(array('environment' => 'sonata'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\Sonata\SonataBackend')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.sonata', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->once())
                ->method('publishInvocation')
                ->with($this->equalTo('test_service'), 'doWork', array('something'));

        $testService->doWork('something');

        $this->assertNull($testService->getPayload());
    }

    public function testSonataConsumer()
    {
        $client = static::createClient(array('environment' => 'sonata'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\Sonata\SonataConsumer')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.rabbitmq', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->never())
                ->method('publishInvocation');

        $consumer = new SonataConsumer($client->getKernel()->getContainer());

        $message = new Message();
        $message->setBody(array('service' => 'test_service', 'method' => 'doWork', 'arguments' => array('something')));
        $event = new ConsumerEvent($message);
        $consumer->process($event);

        $this->assertEquals('something', $testService->getPayload());
    }

}
