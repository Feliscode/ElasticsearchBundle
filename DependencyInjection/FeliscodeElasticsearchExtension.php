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
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['templates'] as $templateName => $templateFilePath) {
            if (false === file_exists($templateFilePath)) {
                throw new RuntimeException(sprintf('File %s does not exist.', $templateFilePath));
            }
        }

        $container->setParameter('feliscode_elasticsearch.config.connections', $config['connections']);
        $container->setParameter('feliscode_elasticsearch.config.templates', $config['templates']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
