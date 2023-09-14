<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Provider;

use Asdoria\SyliusPickupPointPlugin\Api\Soap\SoapClientInterface;
use Asdoria\SyliusPickupPointPlugin\Model\Aware\OpeningAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;
use Setono\SyliusPickupPointPlugin\Provider\Provider;
use stdClass;

/**
 * Class Shop2ShopRelayProvider
 * @package Asdoria\SyliusPickupPointPlugin\Provider
 */
final class Shop2ShopRelayProvider extends Provider
{
    /**
     * @param SoapClientInterface $soapClient
     * @param FactoryInterface    $pickupPointFactory
     */
    public function __construct(
        private SoapClientInterface $soapClient,
        private FactoryInterface    $pickupPointFactory,
        private RequestStack        $requestStack
    )
    {
    }

    /**
     * @param stdClass $point
     *
     * @return PickupPointInterface
     */
    public function transform(stdClass $point): PickupPointInterface
    {
        $pickupPoint = $this->pickupPointFactory->createNew();
        Assert::isInstanceOf($pickupPoint, PickupPointInterface::class);

        $pickupPoint->setCode(new PickupPointCode($point->identifiant, $this->getCode(), 'FR'));
        $pickupPoint->setName($point->nom);
        $pickupPoint->setAddress($point->adresse1);
        $pickupPoint->setZipCode($point->codePostal);
        $pickupPoint->setCity($point->localite);
        $pickupPoint->setCountry('FR');
        $pickupPoint->setLatitude(floatval($point->coordGeolocalisationLatitude));
        $pickupPoint->setLongitude(floatval($point->coordGeolocalisationLongitude));

        if (!$pickupPoint instanceof OpeningAwareInterface) {
            return $pickupPoint;
        }

        $pickupPoint->setOpening($this->formatOpening($point->listeHoraireOuverture));

        return $pickupPoint;
    }

    /**
     * Will return an array of pickup points
     *
     * @return iterable<PickupPointInterface>
     */
    public function findPickupPoints(OrderInterface $order): iterable
    {
        $pickupPoints    = [];
        $shippingAddress = $order->getShippingAddress();

        if (null === $shippingAddress) {
            return [];
        }

        $options = [
            'address'     => $shippingAddress->getStreet(),
            'zipCode'     => $shippingAddress->getPostcode(),
            'city'        => $shippingAddress->getCity(),
            'countryCode' => $shippingAddress->getCountryCode(),
            'productCode' => $shippingAddress->getCountryCode() == 'FR' ? '5C' : '6B',
            'weight'      => $this->getTotalWeight($order),
            'language'    => $order->getLocaleCode(),
        ];


        $points = $this->soapClient->findPickupPoints($options);

        foreach ($points as $point) {
            $pickupPoints[] = $this->transform($point);
        }

        return $pickupPoints;
    }

    /**
     * @param OrderInterface $order
     *
     * @return float
     */
    protected function getTotalWeight(OrderInterface $order): float
    {
        $weight = 0.00;

        foreach ($order->getShipments() as $shipment) {
            $weight += $shipment->getShippingWeight() * 1000;
        }

        return $weight;
    }

    /**
     * @param PickupPointCode $code
     *
     * @return PickupPointInterface|null
     */
    public function findPickupPoint(PickupPointCode $code): ?PickupPointInterface
    {
        $point = $this->soapClient->findPickupPoint(['identifiant' => $code->getIdPart()]);

        if ($point) {
            return $this->transform($point);
        }

        return null;
    }

    /**
     * Format opening days
     *
     * @param array $openingHours
     * @return array
     */
    public function formatOpening(array $openingHours): array
    {
        $days = array(1=>'monday', 2=>'tuesday', 3=>'wednesday', 4=>'thursday', 5=>'friday', 6=>'saturday', 7=>'sunday');

        usort($openingHours, fn($a, $b) => $a->jour > $b->jour);

        $daysOpening = [];
        foreach ($openingHours as $oh_item) {
            $daysOpening[$days[$oh_item->jour]] = str_replace([':', ' '], ['h', ' / '], $oh_item->horairesAsString);
        }

        return $daysOpening;
    }

    /**
     * @return iterable
     */
    public function findAllPickupPoints(): iterable
    {
        dd('ok');
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'shop2shop_relay';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Shop2shop Relay';
    }
}
