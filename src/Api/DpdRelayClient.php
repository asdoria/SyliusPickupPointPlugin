<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Class DpdRelayClient
 * @package Asdoria\SyliusPickupPointPlugin\Api
 */
final class DpdRelayClient extends HttpClient implements RelayClientInterface
{
    /**
     * @param string $uri
     * @param array  $options
     */
    public function __construct(
        protected string $uri,
        protected array  $options = []
    )
    {
        parent::__construct(['base_uri' => $uri]);
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findPickupPoints(array $params): array
    {
        $response = $this->request('GET', 'GetPudoList', [
            'query' => $this->getOptions($params),
        ]);

        return $this->getResult($response) ?? [];
    }

    /**
     * @param array $params
     *
     * @return \stdClass|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function findPickupPoint(array $params): ?\stdClass
    {
        $response = $this->request('GET', 'GetPudoDetails', [
            'query' => $this->getOptions($params),
        ]);

        $result = $this->getResult($response);

        return $result[0] ?? null;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     * @throws \Exception
     */
    protected static function getResult(ResponseInterface $response): array
    {
        $xml  = $response->getBody()->getContents();
        $root = new \SimpleXMLElement($xml);
        if (!isset($root->PUDO_ITEMS->PUDO_ITEM)) {
            return [];
        }
        $points = [];
        foreach ($root->PUDO_ITEMS->PUDO_ITEM as $item) {
            $points[] = json_decode(json_encode($item));
        }

        return $points;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getOptions(array $options = []): array
    {
        return array_merge(
            $this->options,
            $options
        );
    }
}
