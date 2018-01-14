<?php

namespace Feliscode\ElasticsearchBundle\Registry;

use ArrayObject;
use Elastica\Index;
use LogicException;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class IndexRegistry extends ArrayObject
{
    /**
     * @param string $indexName
     * @param Index $index
     * @return void
     */
    public function add(string $indexName, Index $index): void
    {
        if ($this->offsetExists($indexName)) {
            throw new LogicException(sprintf('Index with name %s already exists!', $indexName));
        }

        $this->offsetSet($indexName, $index);
    }

    /**
     * @param string $indexName
     * @return Index
     */
    public function get(string $indexName): Index
    {
        if (false === $this->offsetExists($indexName)) {
            throw new LogicException(sprintf('The index with the name %s does not exist!', $indexName));
        }

        return $this->offsetGet($indexName);
    }
}