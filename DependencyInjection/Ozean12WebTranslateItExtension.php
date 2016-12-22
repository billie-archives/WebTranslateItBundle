<?php

namespace Ozean12\WebTranslateItBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class Ozean12WebTranslateItExtension extends Extension
{
    const REPOSITORY_DEFINITION_ID = 'ozean12.webtranslateit.repository.service';
    const COMMAND_DEFINITION_ID = 'ozean12.webtranslateit.pull.command';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->getDefinition(self::REPOSITORY_DEFINITION_ID)
            ->replaceArgument(0, $config['read_key'])
            ->replaceArgument(1, $config['base_url'])
            ->replaceArgument(2, new Definition('%guzzle.http_client.class%'))
        ;

        $container->getDefinition(self::COMMAND_DEFINITION_ID)
            ->replaceArgument(2, $config['pull_path'])
        ;
    }
}
