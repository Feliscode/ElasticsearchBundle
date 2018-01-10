<?php

namespace Feliscode\ElasticsearchBundle\Registry;

use ArrayObject;
use LogicException;
use Feliscode\ElasticsearchBundle\Client\Client;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class ClientRegistry extends ArrayObject
{
    /**
     * @param string $clientName
     * @param Client $client
     * @return void
     */
    public function add(string $clientName, Client $client): void
    {
        if ($this->offsetExists($clientName)) {
            throw new LogicException(sprintf('Client with name %s already exists!', $clientName));
        }

        $this->offsetSet($clientName, $client);
    }

    /**
     * @param string $clientName
     * @return Client
     */
    public function get(string $clientName): Client
    {
        if (false === $this->offsetExists($clientName)) {
            throw new LogicException(sprintf('The client with the name %s does not exist!', $clientName));
        }

        return $this->offsetGet($clientName);
    }
}