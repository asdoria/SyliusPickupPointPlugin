<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

use Asdoria\SyliusPickupPointPlugin\Api\Soap\BaseSoapClient;

/**
 * Class ColissimoRelayClient
 * @package Asdoria\SyliusPickupPointPlugin\Api
 */
final class ColissimoRelayClient extends BaseSoapClient implements RelayClientInterface
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
            ->__soapCall('findRDVPointRetraitAcheminement', $this->getOptions(array_merge($params, [
                'shippingDate' => date('d/m/Y')
            ])));

        if (!isset($response->return) || !isset($response->return->listePointRetraitAcheminement)) {
            return [];
        }

        return $response->return->listePointRetraitAcheminement;
    }

    /**
     * @param array $params
     *
     * @return \stdClass|null
     * @throws \SoapFault
     */
    public function findPickupPoint(array $params): ?\stdClass
    {
        $response = $this->getClient()->__soapCall('findPointRetraitAcheminementByID', $this->getOptions(array_merge($params, [
            'date' => date('d/m/Y')
        ])));

        return $response->return->pointRetraitAcheminement;
    }
}
