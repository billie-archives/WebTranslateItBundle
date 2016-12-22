<?php

namespace Ozean12\WebTranslateItBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * {@inheritdoc}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ozean12_web_translate_it');

        $rootNode
            ->children()
                ->scalarNode('base_url')->defaultValue('https://webtranslateit.com/api/')->end()
                ->scalarNode('read_key')->cannotBeEmpty()->end()
                ->scalarNode('pull_path')->cannotBeEmpty()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
