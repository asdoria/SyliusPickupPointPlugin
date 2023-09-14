<?php
declare(strict_types=1);


namespace Asdoria\SyliusPickupPointPlugin\DependencyInjection;

use Asdoria\SyliusPickupPointPlugin\Api\ChronoRelayClient;
use Asdoria\SyliusPickupPointPlugin\Api\ColissimoRelayClient;
use Asdoria\SyliusPickupPointPlugin\Api\DpdRelayClient;
use Asdoria\SyliusPickupPointPlugin\Api\MondialRelayClient;

use Asdoria\SyliusPickupPointPlugin\Api\Shop2ShopRelayClient;
use Asdoria\SyliusPickupPointPlugin\Provider\ChronoRelayProvider;
use Asdoria\SyliusPickupPointPlugin\Provider\ColissimoRelayProvider;
use Asdoria\SyliusPickupPointPlugin\Provider\DpdRelayProvider;
use Asdoria\SyliusPickupPointPlugin\Provider\MondialRelayProvider;
use Asdoria\SyliusPickupPointPlugin\Provider\Shop2ShopRelayProvider;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Asdoria\SyliusPickupPointPlugin\DependencyInjection
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder|void
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('asdoria_sylius_pickup_point_plugin');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('chrono_relay')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('Chronopost Relay')->end()
                        ->scalarNode('enabled')->defaultFalse()->end()
                        ->scalarNode('uri')->defaultValue('https://www.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl')->end()
                        ->scalarNode('client')->defaultValue(ChronoRelayClient::class)->end()
                        ->scalarNode('provider')->defaultValue(ChronoRelayProvider::class)->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('accountNumber')->defaultNull()->end()
                                ->scalarNode('password')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('colissimo_relay')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('Colissimo Relay')->end()
                        ->scalarNode('enabled')->defaultFalse()->end()
                        ->scalarNode('uri')->defaultValue('https://ws.colissimo.fr/pointretrait-ws-cxf/PointRetraitServiceWS/2.0?wsdl')->end()
                        ->scalarNode('provider')->defaultValue(ColissimoRelayProvider::class)->end()
                        ->scalarNode('client')->defaultValue(ColissimoRelayClient::class)->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('accountNumber')->defaultNull()->end()
                                ->scalarNode('password')->defaultNull()->end()
                                ->scalarNode('filterRelay')->defaultValue('1')->end()
                                ->scalarNode('optionInter')->defaultValue('0')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('shop2shop_relay')
                    ->addDefaultsIfNotSet()
                    ->children()
                            ->scalarNode('name')->defaultValue('Shop2shop Relay')->end()
                            ->scalarNode('enabled')->defaultFalse()->end()
                            ->scalarNode('uri')->defaultValue('https://www.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl')->end()
                            ->scalarNode('provider')->defaultValue(Shop2ShopRelayProvider::class)->end()
                            ->scalarNode('client')->defaultValue(Shop2ShopRelayClient::class)->end()
                            ->arrayNode('options')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('accountNumber')->defaultNull()->end()
                                    ->scalarNode('password')->defaultNull()->end()
                                    ->scalarNode('maxPointChronopost')->defaultValue('5')->end()
                                    ->scalarNode('maxDistanceSearch')->defaultValue('20')->end()
                                    ->scalarNode('version')->defaultValue('2.0')->end()
                                    ->scalarNode('holidayTolerant')->defaultValue('1')->end()
                                    ->scalarNode('type')->defaultValue('T')->info('Type de point Chronopost recherché => A : Agence Chronopost,B : Bureau de poste,P : Point relais + consigne,C : Point relais sans consigne,T : Tout type de point')->end()
                                    ->scalarNode('service')->defaultValue('T')->info('Nature de la prestation liée à la recherche => L : livraison en point Chronopost,D : dépôt,I : instance,T : Tout type de prestation')->end()
                                ->end()
                            ->end()
                    ->end()
                ->end()
                ->arrayNode('dpd_relay')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('Dpd Relay')->end()
                        ->scalarNode('enabled')->defaultFalse()->end()
                        ->scalarNode('uri')->defaultValue('https://mypudo.pickup-services.com/mypudo/mypudo.asmx/')->end()
                        ->scalarNode('provider')->defaultValue(DpdRelayProvider::class)->end()
                        ->scalarNode('client')->defaultValue(DpdRelayClient::class)->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('carrier')->defaultValue('EXA')->end()
                                ->scalarNode('key')->defaultValue('deecd7bc81b71fcc0e292b53e826c48f')->end()
                                ->scalarNode('holiday_tolerant')->defaultValue('1')->end()
                                ->scalarNode('max_pudo_number')->defaultValue('5')->end()
                                ->scalarNode('max_distance_search')->defaultValue('20')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mondial_relay')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('Mondial Relay')->end()
                        ->scalarNode('enabled')->defaultFalse()->end()
                        ->scalarNode('uri')->defaultValue('https://www.mondialrelay.fr/WebService/Web_Services.asmx?WSDL')->end()
                        ->scalarNode('provider')->defaultValue(MondialRelayProvider::class)->end()
                        ->scalarNode('client')->defaultValue(MondialRelayClient::class)->end()
                        ->arrayNode('options')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('accountNumber')->defaultNull()->end()
                                ->scalarNode('password')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
