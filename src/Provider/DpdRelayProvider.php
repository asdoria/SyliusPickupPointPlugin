<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Provider;

use Asdoria\SyliusPickupPointPlugin\Model\Aware\OpeningAwareInterface;
use GuzzleHttp\ClientInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;
use Setono\SyliusPickupPointPlugin\Provider\Provider;
use stdClass;

/**
 * Class DpdRelayProvider
 * @package Asdoria\SyliusPickupPointPlugin\Provider
 */
final class DpdRelayProvider extends Provider
{
    /**
     * @param ClientInterface  $httpClient
     * @param FactoryInterface $pickupPointFactory
     */
    public function __construct(
        private ClientInterface $httpClient,
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
    public function transform(\stdClass $point): PickupPointInterface
    {
        $pickupPoint = $this->pickupPointFactory->createNew();
        Assert::isInstanceOf($pickupPoint, PickupPointInterface::class);

        $pickupPoint->setCode(new PickupPointCode((string) $point->PUDO_ID, $this->getCode(), 'FR'));
        $pickupPoint->setName((string) $point->NAME);
        $pickupPoint->setAddress((string) $point->ADDRESS1);
        $pickupPoint->setZipCode((string) $point->ZIPCODE);
        $pickupPoint->setCity((string) $point->CITY);
        $pickupPoint->setCountry('FR');
        $pickupPoint->setLatitude(floatval(str_replace(',', '.', (string) $point->LATITUDE)));
        $pickupPoint->setLongitude(floatval(str_replace(',', '.', (string) $point->LONGITUDE)));

        if (!$pickupPoint instanceof OpeningAwareInterface) {
            return $pickupPoint;
        }

        $pickupPoint->setOpening($this->formatOpening($point->OPENING_HOURS_ITEMS->OPENING_HOURS_ITEM));

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
        $request         = $this->requestStack->getMainRequest();
        if (null === $shippingAddress) {
            return [];
        }

        $options = [
            'countryCode'         => $shippingAddress->getCountryCode(),
            'zipCode'             => $request->get('postCode', $shippingAddress->getPostcode()),
            'city'                => $shippingAddress->getCity(),
            'requestID'           => $shippingAddress->getId(),
            'date_from'           => (new \DateTime('+1 day'))->format('d/m/Y'),
            'address'             => $shippingAddress->getStreet(),
            'weight'              => 1.0,
            'category'            => ''
        ];


        $points = $this->httpClient->findPickupPoints($options);

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
        $point = $this->httpClient->findPickupPoint(['pudo_id' => $code->getIdPart()]);

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

        $daysOpening = [];
        foreach ($openingHours as $oh_item) {
            $hours = str_replace(':', 'h', $oh_item->START_TM).'-'.str_replace(':', 'h', $oh_item->END_TM);

            $daysOpening[$days[$oh_item->DAY_ID]] = isset($daysOpening[$days[$oh_item->DAY_ID]]) ?
                $daysOpening[$days[$oh_item->DAY_ID]] . ' / ' . $hours :
                $hours
            ;
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
        return 'dpd_relay';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Dpd Relay';
    }
}
