<?php

namespace Feliscode\ElasticsearchBundle\DependencyInjection\Compiler;

use Feliscode\ElasticsearchBundle\Client\Client;
use Feliscode\ElasticsearchBundle\Manager\IndexManager;
use Feliscode\ElasticsearchBundle\Proxy\Index;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class ClientsCompiler implements CompilerPassInterface
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $this->containerBuilder = $container;
        $clientList = $container->getParameter('feliscode_elasticsearch.clients');

        if (empty($clientList)) {
            return;
        }

        $this->processClientList($clientList);
    }

    /**
     * @param array $clientList
     * @return void
     */
    private function processClientList(array $clientList): void
    {
        $clientRegistryDefinition = $this->containerBuilder->findDefinition('feliscode_elasticsearch.registry.client');

        foreach ($clientList as $clientName => $clientData) {
            $clientServiceId = sprintf('feliscode_elasticsearch.client.%s', $clientName);

            $clientDefinition = $this->containerBuilder->register(
                $clientServiceId,
                Client::class
            );
            $clientDefinition->addArgument($clientName);
            $clientDefinition->addArgument($clientData['connections']);

            $clientRegistryDefinition->addMethodCall('add', [$clientName, $clientServiceId]);

            $this->processIndexList($clientDefinition, $clientName);
        }
    }

    /**
     * @param Definition $clientRegistryDefinition
     * @param string $clientName
     * @return void
     */
    private function processIndexList(Definition $clientRegistryDefinition, string $clientName): void
    {
        $indexRegistryDefinition = $this->containerBuilder->findDefinition('feliscode_elasticsearch.registry.index');
        $indexList = $this->containerBuilder->getParameter(
            sprintf('feliscode_elasticsearch.%s.indexes', $clientName)
        );
        if (empty($indexList)) {
            return;
        }

        foreach ($indexList as $indexName => $indexData) {
            $indexServiceDefinition = $this->createIndexServiceDefinition(
                $clientRegistryDefinition,
                $indexName,
                $indexData['name'],
                $indexData['type']
            );
            
            $indexManagerServiceId = sprintf('feliscode_elasticsearch.manager.%s', $indexName);

            if (isset($indexData['service'])) {
                //todo: check definition is instance of AbstractIndexManager
                $this->containerBuilder->setAlias($indexManagerServiceId, $indexData['service']);
            } else {
                $indexManagerDefinition = $this->containerBuilder->register(
                    $indexManagerServiceId,
                    IndexManager::class
                );
                $indexManagerDefinition->addArgument($clientRegistryDefinition);
                $indexManagerDefinition->addArgument($indexServiceDefinition);
            }

            $indexRegistryDefinition->addMethodCall('add', [$indexName, $indexServiceDefinition]);
        }

    }

    /**
     * @param Definition $clientRegistryDefinition
     * @param string $indexConfigName
     * @param string $indexTitle
     * @param string $indexType
     * @return Definition
     */
    private function createIndexServiceDefinition(
        Definition $clientRegistryDefinition,
        string $indexConfigName,
        string $indexTitle,
        string $indexType): Definition
    {
        $indexServiceId = sprintf('feliscode_elasticsearch.index.%s', $indexConfigName);
        $indexServiceDefinition = $this->containerBuilder->register(
            $indexServiceId,
            Index::class
        );

        $indexServiceDefinition->addArgument($clientRegistryDefinition);
        $indexServiceDefinition->addArgument($indexTitle);
        $indexServiceDefinition->addArgument($indexType);

        return $indexServiceDefinition;
    }
}
