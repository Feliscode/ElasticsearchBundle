<?php

namespace Feliscode\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Dominik Pawelec <dominik@feliscode.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('feliscode_elasticsearch');

        $rootNode
            ->children()

                ->arrayNode('clients')
                    ->useAttributeAsKey('client_name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('host')->end()
                            ->scalarNode('port')->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('templates')
                    ->useAttributeAsKey('template_name')
                        ->variablePrototype()
                    ->end()
                ->end()

                ->arrayNode('indexes')
                    ->useAttributeAsKey('index_name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('properties')
                                ->useAttributeAsKey('property_name')
                                ->arrayPrototype()
                                    ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
