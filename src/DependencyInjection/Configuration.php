<?php

namespace Analogic\CryptocurrencyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('analogic_bitcoind');

        $rootNode
            ->children()
            ->scalarNode('dsn')->end()
            ->scalarNode('account')->end()
            ->integerNode('estimate_fees_blocks')->defaultValue(4)->end()
            ->scalarNode('listen')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
