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

use Metadata\MetadataFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Dubture\AsyncBundle\Tests\Controller
 */
class RuntimeBackendTest extends WebTestCase
{
    public function testRuntimeBackend()
    {
        $client = static::createClient(array('environment' => 'runtime'));

        /** @var MetadataFactory $factory */
        $factory = $client->getKernel()->getContainer()->get('jms_di_extra.metadata.metadata_factory');

        $md = $factory->getMetadataForClass('TestService');

        $this->assertNotNull($md);

        $testService = $client->getKernel()->getContainer()->get('test_service');

        $testService->doWork('something');

        $this->assertEquals('something', $testService->getPayload());
    }
}
