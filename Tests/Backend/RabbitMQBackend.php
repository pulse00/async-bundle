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

use Dubture\AsyncBundle\RabbitMQ\RabbitMQConsumer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Dubture\AsyncBundle\Tests\Controller
 */
class RabbitMQBackendTest extends WebTestCase
{
    public function testRabbitMQBackend()
    {
        $client = static::createClient(array('environment' => 'rabbit'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\RabbitMQ\RabbitMQBackend')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.rabbitmq', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->once())
                ->method('publishInvocation')
                ->with($this->equalTo('test_service'), 'doWork', array('something'));

        $testService->doWork('something');

        $this->assertNull($testService->getPayload());
    }

    public function testRabbitMQConsumer()
    {
        $client = static::createClient(array('environment' => 'rabbit'));

        $backend = $this->getMockBuilder('\Dubture\AsyncBundle\RabbitMQ\RabbitMQBackend')
                ->disableOriginalConstructor()
                ->getMock();

        $client->getKernel()->getContainer()->set('dubture.async.backend.rabbitmq', $backend);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $backend->expects($this->never())
                ->method('publishInvocation');

        $consumer = new RabbitMQConsumer($client->getKernel()->getContainer());

        $message = $this->getMockBuilder('\PhpAmqpLib\Message\AMQPMessage')
                ->disableOriginalConstructor()
                ->getMock();

        $message->body = serialize(array('service' => 'test_service', 'method' => 'doWork', 'arguments' => array('something')));

        $consumer->execute($message);

        $this->assertEquals('something', $testService->getPayload());
    }

}
