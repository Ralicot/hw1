<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $rootNode
            ->children()
                ->arrayNode('dashboard')
                    ->children()
                        ->arrayNode('items')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('title')
                                        ->defaultValue(null)
                                    ->end()
                                    ->scalarNode('route')->end()
                                    ->scalarNode('icon')
                                        ->defaultValue(null)
                                    ->end()
                                    ->booleanNode('active')
                                        ->defaultValue(true)
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        $rootNode ->children()
            ->arrayNode('jsonrpc')
                ->children()
                    ->arrayNode('functions')
                    ->useAttributeAsKey('function')
                    ->prototype('array')
                        ->children()
                        ->scalarNode('service')->end()
                        ->scalarNode('method')->end()
                        ->arrayNode('jms_serialization_context')
                            ->children()
                            ->arrayNode('groups')
                                ->beforeNormalization()
                                ->ifTrue(function ($v) { return is_string($v); })
                                ->then(function ($v) { return array($v); })
                            ->end()
                            ->prototype('scalar')->end()
        ->end()
        ->scalarNode('version')->end()
        ->booleanNode('max_depth_checks')->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end();
        return $treeBuilder;
    }

}
