<?php

namespace Feliscode\ElasticsearchBundle;

use Feliscode\ElasticsearchBundle\DependencyInjection\Compiler\ClientsCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class FeliscodeElasticsearchBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ClientsCompiler());
    }
}
