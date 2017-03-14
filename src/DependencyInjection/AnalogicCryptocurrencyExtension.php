<?php

namespace Analogic\CryptocurrencyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AnalogicCryptocurrencyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias().'.bitcoind.dsn', $config['bitcoind']['dsn']);
        $container->setParameter($this->getAlias().'.bitcoind.listen', $config['bitcoind']['listen']);
        $container->setParameter($this->getAlias().'.bitcoind.account', $config['bitcoind']['account']);
        $container->setParameter($this->getAlias().'.bitcoind.estimate_fees_blocks', $config['bitcoind']['estimate_fees_blocks']);

        $container->setParameter($this->getAlias().'.litecoind.dsn', $config['litecoind']['dsn']);
        $container->setParameter($this->getAlias().'.litecoind.listen', $config['litecoind']['listen']);
        $container->setParameter($this->getAlias().'.litecoind.account', $config['litecoind']['account']);
        $container->setParameter($this->getAlias().'.litecoind.estimate_fees_blocks', $config['litecoind']['estimate_fees_blocks']);

        $container->setParameter($this->getAlias().'.dashd.dsn', $config['dashd']['dsn']);
        $container->setParameter($this->getAlias().'.dashd.listen', $config['dashd']['listen']);
        $container->setParameter($this->getAlias().'.dashd.account', $config['dashd']['account']);
        $container->setParameter($this->getAlias().'.dashd.estimate_fees_blocks', $config['dashd']['estimate_fees_blocks']);

        $container->setParameter($this->getAlias().'.dogecoind.dsn', $config['dogecoind']['dsn']);
        $container->setParameter($this->getAlias().'.dogecoind.listen', $config['dogecoind']['listen']);
        $container->setParameter($this->getAlias().'.dogecoind.account', $config['dogecoind']['account']);
        $container->setParameter($this->getAlias().'.dogecoind.estimate_fees_blocks', $config['dogecoind']['estimate_fees_blocks']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
