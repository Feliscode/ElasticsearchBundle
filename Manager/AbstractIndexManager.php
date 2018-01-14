<?php

namespace Feliscode\ElasticsearchBundle\Manager;

use Elastica\Query;
use Elastica\Search;
use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Proxy\Index;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
abstract class AbstractIndexManager implements IndexManagerInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Index
     */
    private $index;

    /**
     * @param Client $client
     * @param Index $index
     */
    public function __construct(Client $client, Index $index)
    {
        $this->client = $client;
        $this->index = $index;
    }

    /**
     * {@inheritdoc}
     */
    public function save(object $object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $search = new Search($this->client);
        $search->addIndex($this->index);
        $search->addType($this->index->getType());

        $query = new Query();
        $query->setSize(1);
        //...
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $string)
    {

    }
}