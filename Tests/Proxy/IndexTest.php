<?php

namespace Feliscode\ElasticsearchBundle\Tests\Registry;

use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Exception\NotExistingTypeException;
use Feliscode\ElasticsearchBundle\Proxy\Index;
use PHPUnit\Framework\TestCase;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class IndexTest extends TestCase
{
    /**
     * @covers Index::getType()
     */
    public function testGetType()
    {
        $clientMock = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();

        $index = new Index($clientMock, 'index-name', 'type-name');

        $this->assertTrue(is_object($index->getType('type-name')));

        $this->expectException(NotExistingTypeException::class);
        $index->getType('not-defined-type');
    }
}
