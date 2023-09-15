<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

use Asdoria\SyliusPickupPointPlugin\Api\Soap\BaseSoapClient;

/**
 * Class Shop2ShopRelayClient
 * @package Asdoria\SyliusPickupPointPlugin\Api
 */
final class Shop2ShopRelayClient extends BaseSoapClient implements RelayClientInterface
{
    /**
     * @param array $params
     *
     * @return array
     * @throws \SoapFault
     */
    public function findPickupPoints(array $params): array
    {
        $response = $this->getClient()
            ->__soapCall('recherchePointChronopostInter', $this->getOptions(array_merge($params, [
                'shippingDate' => date('d/m/Y')
            ])));

        return $response->return->listePointRelais ?? [];
    }

    /**
     * @param array $params
     *
     * @return \stdClass|null
     * @throws \SoapFault
     */
    public function findPickupPoint(array $params): ?\stdClass
    {
        $response = $this->getClient()->__soapCall('rechercheDetailPointChronopostInter', $this->getOptions($params));

        return $response->return->listePointRelais;
    }
}
