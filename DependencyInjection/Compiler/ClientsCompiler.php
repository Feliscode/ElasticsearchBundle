<?php

namespace Feliscode\ElasticsearchBundle\DependencyInjection\Compiler;

use Feliscode\ElasticsearchBundle\Client\Client;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class ClientsCompiler implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $clients = $container->getParameter('feliscode_elasticsearch.clients');

        if (empty($clients)) {
            return;
        }

        $clientRegistryDefinition = $container->findDefinition('feliscode_elasticsearch.registry.client');

        foreach ($clients as $clientName => $clientData) {
            $definition = $container->register(
                sprintf('feliscode_elasticsearch.client.%s', $clientName),
                Client::class
            );
            $definition->addArgument($clientData['connections']);

            $clientRegistryDefinition->addMethodCall('add', [$definition]);
        }
    }

}