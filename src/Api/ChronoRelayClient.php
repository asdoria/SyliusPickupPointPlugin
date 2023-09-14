<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

use Asdoria\SyliusPickupPointPlugin\Api\Soap\BaseSoapClient;

/**
 * Class ChronoRelayClient
 * @package Asdoria\SyliusPickupPointPlugin\Api
 */
final class ChronoRelayClient extends BaseSoapClient implements RelayClientInterface
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
            ->__soapCall('rechercheBtParCodeproduitEtCodepostalEtDate', $this->getOptions(array_merge($params, [
                'date' => date('d/m/Y')
            ])));

        return $response->return;
    }

    /**
     * @param array $params
     *
     * @return \stdClass|null
     * @throws \SoapFault
     */
    public function findPickupPoint(array $params): ?\stdClass
    {
        $response = $this->getClient()->__soapCall('rechercheBtParIdChronopostA2Pas', $this->getOptions($params));

        return $response->return;
    }
}
