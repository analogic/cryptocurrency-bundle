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
        $rootNode = $treeBuilder->root('analogic_cryptocurrency');

        $rootNode
            ->children()
                ->arrayNode('bitcoind')
                    ->children()
                        ->scalarNode('dsn')->end()
                        ->scalarNode('account')->end()
                        ->integerNode('estimate_fees_blocks')->defaultValue(4)->end()
                        ->scalarNode('listen')->end()
                    ->end()
                ->end()
                ->arrayNode('litecoind')
                    ->children()
                        ->scalarNode('dsn')->end()
                        ->scalarNode('account')->end()
                        ->integerNode('estimate_fees_blocks')->defaultValue(4)->end()
                        ->scalarNode('listen')->end()
                    ->end()
                ->end()
                ->arrayNode('dashd')
                    ->children()
                        ->scalarNode('dsn')->end()
                        ->scalarNode('account')->end()
                        ->integerNode('estimate_fees_blocks')->defaultValue(4)->end()
                        ->scalarNode('listen')->end()
                    ->end()
                ->end()
                ->arrayNode('dogecoind')
                    ->children()
                        ->scalarNode('dsn')->end()
                        ->scalarNode('account')->end()
                        ->integerNode('estimate_fees_blocks')->defaultValue(4)->end()
                        ->scalarNode('listen')->end()
                    ->end()
                ->end()
                ->arrayNode('monerod')
                    ->children()
                        ->scalarNode('dsn')->end()
                    ->end()
                ->end()

//               ->arrayNode('ethereumd')
//                    ->children()
//                       ->scalarNode('dsn')->end()
//                       ->scalarNode('account')->end()
//                    ->end()
//               ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
