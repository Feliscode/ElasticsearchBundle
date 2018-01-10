<?php

namespace Feliscode\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class FeliscodeElasticsearchExtension extends Extension
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->containerBuilder = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->containerBuilder->setParameter('feliscode_elasticsearch.clients', $config['clients']);

        foreach ($config['clients'] as $clientName => $clientData) {
            $this->initClientConfig($clientName, $clientData);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param string $clientName
     * @param array $clientConfigData
     * @return void
     */
    private function initClientConfig(string $clientName, array $clientConfigData): void
    {

        foreach ($clientConfigData['templates'] as $templateName => $templateFilePath) {
            if (false === file_exists($templateFilePath)) {
                throw new RuntimeException(sprintf('File %s does not exist.', $templateFilePath));
            }
        }

        $this->containerBuilder->setParameter(
            sprintf('feliscode_elasticsearch.%s.templates', $clientName),
            $clientConfigData['templates']
        );
        $this->containerBuilder->setParameter(
            sprintf('feliscode_elasticsearch.%s.indexes', $clientName),
            $clientConfigData['indexes']
        );
    }
}
