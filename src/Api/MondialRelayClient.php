<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

use Asdoria\SyliusPickupPointPlugin\Api\Soap\BaseSoapClient;

/**
 * Class MondialRelayClient
 * @package Asdoria\SyliusPickupPointPlugin\Api
 */
final class MondialRelayClient extends BaseSoapClient implements RelayClientInterface
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function findPickupPoints(array $params): array
    {
        $response =  $this->getClient()->WSI3_PointRelais_Recherche($this->getOptions($params));

        if (!isset($response->WSI3_PointRelais_RechercheResult->PointsRelais)) {
            return [];
        }

        return $response->WSI3_PointRelais_RechercheResult->PointsRelais->PointRelais_Details;
    }

    /**
     * @param array $params
     *
     * @return \stdClass|null
     * @throws \SoapFault
     */
    public function findPickupPoint(array $params): ?\stdClass
    {
        $response = $this->getClient()->WSI2_DetailPointRelais($this->getOptions($params));

        return $response->WSI2_DetailPointRelaisResult;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getOptions(array $options = []) : array
    {
        $password = $this->options['password'] ?? null;

        $options = array('Enseigne' => $this->options['accountNumber'] ?? null) + $options;

        $options['Security'] = strtoupper(
            md5(implode('', $options) . $password)
        );

        return $options;
    }
}
