<?php
declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AsdoriaPickupPointExtension
 * @package Asdoria\SyliusPickupPointPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class AsdoriaSyliusPickupPointExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configs = $this->processConfiguration($this->getConfiguration([], $container), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        foreach ($configs as $key => $config) {
            $this->initParametersProvider($container, $config, $key);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function initParametersProvider(ContainerBuilder $container, array $config, string $providerName): void
    {
        [
            'client'   => $client,
            'uri'      => $uri,
            'options'  => $options,
            'provider' => $provider,
            'name'     => $name,
            'enabled'  => $enabled
        ] = $config;

        if ((!$enabled) || empty($client) || $container->hasDefinition($client)) return;

        $clientDefinition = $container->register($client, $client)
            ->setPublic(true)
            ->addArgument($uri)
            ->addArgument($options);

        if (empty($provider) || $container->hasDefinition($provider)) return;

        $factory = new Reference('setono_sylius_pickup_point.factory.pickup_point');
        $requestStack = new Reference('request_stack');
        $container->register($provider, $provider)
            ->setPublic(true)
            ->addArgument($clientDefinition)
            ->addArgument($factory)
            ->addArgument($requestStack)
            ->addTag('setono_sylius_pickup_point.provider', ['code' => $providerName , 'label' => $name]);
    }
}
