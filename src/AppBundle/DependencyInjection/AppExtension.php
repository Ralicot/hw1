<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class AppExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {


        $configuration = new Configuration();
        $appConfig = $this->processConfiguration($configuration, $configs);

        $dashboardDefinition = $container->register('app.dashboard', 'AppBundle\Dashboard\Dashboard');
        $dashboardDefinition->addMethodCall('loadFromConfiguration', array($appConfig));

        $jsonDefinition = $container->register('app.jsonrpc', 'AppBundle\Service\JsonRPC');
        $jsonDefinition->addMethodCall('loadConfig', array($appConfig));




    }

}
