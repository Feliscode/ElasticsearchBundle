<?php

namespace Feliscode\ElasticsearchBundle\Exception;

use LogicException;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class NotExistingTypeException extends LogicException
{
    /**
     * @param string $notExistingTypeName
     * @param string $indexName
     */
    public function __construct(string $notExistingTypeName, string $indexName)
    {
        parent::__construct(sprintf('Type with name %s is not defined in config of % index', $notExistingTypeName, $indexName));
    }
}