<?php

namespace Feliscode\ElasticsearchBundle\Client;

use Elastica\Client as ElasticaClient;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class Client extends ElasticaClient
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @param array $connectionsConfig
     */
    public function __construct(string $name, array $connectionsConfig)
    {
        parent::__construct($connectionsConfig);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}