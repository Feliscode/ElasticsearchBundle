<?php

namespace Feliscode\ElasticsearchBundle\Manager;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
interface IndexManagerInterface
{
    /**
     * @param object $object
     * @return array|object[]
     */
    public function save(object $object);

    /**
     * @param string|int $id
     * @return object
     */
    public function find($id);

    /**
     * @param string $string
     * @return array|object[]
     */
    public function search(string $string);
}