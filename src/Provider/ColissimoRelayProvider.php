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
 * Class ColissimoRelayProvider
 * @package Asdoria\SyliusPickupPointPlugin\Provider
 */
final class ColissimoRelayProvider extends Provider
{
    /**
     * @param SoapClientInterface $soapClient
     * @param FactoryInterface    $pickupPointFactory
     */
    public function __construct(
        private SoapClientInterface $soapClient,
        private FactoryInterface $pickupPointFactory,
        private RequestStack $requestStack
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

        $pickupPoint->setCode(new PickupPointCode($point->identifiant, $this->getCode(), $point->codePays));
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

        $pickupPoint->setOpening([
            'monday'    => $this->formatOpening($point->horairesOuvertureLundi),
            'tuesday'   => $this->formatOpening($point->horairesOuvertureMardi),
            'wednesday' => $this->formatOpening($point->horairesOuvertureMercredi),
            'thursday'  => $this->formatOpening($point->horairesOuvertureJeudi),
            'friday'    => $this->formatOpening($point->horairesOuvertureVendredi),
            'saturday'  => $this->formatOpening($point->horairesOuvertureSamedi),
            'sunday'    => $this->formatOpening($point->horairesOuvertureDimanche)
        ]);

        return $pickupPoint;
    }

    /**
     * Will return an array of pickup points
     *
     * @return iterable<PickupPointInterface>
     */
    public function findPickupPoints(OrderInterface $order): iterable
    {
        $pickupPoints = [];
        $shippingAddress = $order->getShippingAddress();

        if (null === $shippingAddress) {
            return [];
        }

        $options = [
            'address'       => $shippingAddress->getStreet(),
            'zipCode'       => $shippingAddress->getPostcode(),
            'city'          => $shippingAddress->getCity(),
            'countryCode'   => $shippingAddress->getCountryCode(),
        ];


        $points = $this->soapClient->findPickupPoints($options);

        foreach ($points as $point) {
            $pickupPoints[] = $this->transform($point);
        }

        return $pickupPoints;
    }

    /**
     * @param PickupPointCode $code
     *
     * @return PickupPointInterface|null
     */
    public function findPickupPoint(PickupPointCode $code): ?PickupPointInterface
    {
        $point = $this->soapClient->findPickupPoint(['id' => $code->getIdPart()]);

        if ($point) {
            return $this->transform($point);
        }

        return null;
    }

    /**
     * Format opening day
     *
     * @param stdClass $opening
     * @return string
     */
    public function formatOpening(string $opening): string
    {
        return str_replace([':', ' '], ['h', ' / '], $opening);
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
        return 'colissimo_relay';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Colissimo Relay';
    }
}
