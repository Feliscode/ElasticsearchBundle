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
                    ->isRequired()
                    ->useAttributeAsKey('client_name')
                    ->arrayPrototype()
                    ->children()

                        ->arrayNode('connections')
                            ->isRequired()
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
                            ->isRequired()
                            ->useAttributeAsKey('index_name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')->isRequired()->end()
                                    ->scalarNode('type')->isRequired()->end()
                                    ->scalarNode('model')->isRequired()->end()
                                    ->scalarNode('manager')->defaultNull()->end()
                                    ->arrayNode('fields')
                                        ->useAttributeAsKey('field')
                                        ->arrayPrototype()
                                            ->scalarPrototype()
                                        ->end()
                                    ->end()
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
