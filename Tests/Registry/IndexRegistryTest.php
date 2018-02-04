<?php

namespace Feliscode\ElasticsearchBundle\Tests\Registry;

use Feliscode\ElasticsearchBundle\Proxy\Index;
use Feliscode\ElasticsearchBundle\Registry\IndexRegistry;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class IndexRegistryTest extends TestCase
{
    /**
     * @var IndexRegistry
     */
    private $indexRegistry;

    /**
     * @covers IndexRegistry::add()
     * @covers IndexRegistry::get()
     */
    public function testAddAndGet()
    {
        $indexMock = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $this->indexRegistry->add('some-index', $indexMock);

        $this->assertEquals($indexMock, $this->indexRegistry->get('some-index'));
    }

    /**
     * @covers IndexRegistry::add()
     */
    public function testAddException()
    {
        $indexMock = $this->getMockBuilder(Index::class)->disableOriginalConstructor()->getMock();
        $this->indexRegistry->add('some-index-name', $indexMock);

        $this->expectException(LogicException::class);

        $this->indexRegistry->add('some-index-name', (clone $indexMock));
    }

    /**
     * @covers IndexRegistry::get()
     */
    public function testGetException()
    {
        $this->expectException(LogicException::class);

        $this->indexRegistry->get('Not defined index');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->indexRegistry = new IndexRegistry();
    }
}