<?php

namespace Feliscode\ElasticsearchBundle\Proxy;

use Elastica\Type;
use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Exception\NotExistingTypeException;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class Index extends \Elastica\Index
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param Client $client
     * @param string $name
     * @param string $type
     */
    public function __construct(Client $client, string $name, string $type)
    {
        parent::__construct($client, $name);
        $this->type = $type;
    }

    /**
     * @param null $type
     * @return Type
     */
    public function getType($type = null)
    {
        if (false === is_null($type) && $type !== $this->type) {
            throw new NotExistingTypeException($type, $this->getName());
        }

        return parent::getType($this->type);
    }
}