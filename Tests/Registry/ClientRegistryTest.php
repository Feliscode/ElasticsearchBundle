<?php

namespace Feliscode\ElasticsearchBundle\Tests\Registry;

use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Registry\ClientRegistry;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class ClientRegistryTest extends TestCase
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @covers ClientRegistry::add()
     * @covers ClientRegistry::get()
     */
    public function testAddAndGet()
    {
        $firstClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $this->clientRegistry->add('Example-client', $firstClient);

        $this->assertEquals($firstClient, $this->clientRegistry->get('Example-client'));
    }

    /**
     * @covers ClientRegistry::add()
     */
    public function testAddException()
    {
        $firstClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $this->clientRegistry->add('Example-client', $firstClient);

        $this->expectException(LogicException::class);

        $this->clientRegistry->add('Example-client', (clone $firstClient));
    }

    /**
     * @covers ClientRegistry::get()
     */
    public function testGetException()
    {
        $this->expectException(LogicException::class);

        $this->clientRegistry->get('Not defined client');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->clientRegistry = new ClientRegistry();
    }
}