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
 * Class MondialRelayProvider
 * @package Asdoria\SyliusPickupPointPlugin\Provider
 */
final class MondialRelayProvider extends Provider
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

        $pickupPoint->setCode(new PickupPointCode($point->Num, $this->getCode(), $point->Pays));
        $pickupPoint->setName(trim($point->LgAdr1));
        $pickupPoint->setAddress(trim($point->LgAdr3));
        $pickupPoint->setZipCode($point->CP);
        $pickupPoint->setCity(trim($point->Ville));
        $pickupPoint->setCountry($point->Pays);
        $pickupPoint->setLatitude(floatval(str_replace(',', '.', str_replace('.', '', $point->Latitude ?? ''))));
        $pickupPoint->setLongitude(floatval(str_replace(',', '.', str_replace('.', '', $point->Longitude ??''))));

        if (!$pickupPoint instanceof OpeningAwareInterface) {
            return $pickupPoint;
        }

        $pickupPoint->setOpening([
            'monday'    => $this->formatOpening($point->Horaires_Lundi),
            'tuesday'   => $this->formatOpening($point->Horaires_Mardi),
            'wednesday' => $this->formatOpening($point->Horaires_Mercredi),
            'thursday'  => $this->formatOpening($point->Horaires_Jeudi),
            'friday'    => $this->formatOpening($point->Horaires_Vendredi),
            'saturday'  => $this->formatOpening($point->Horaires_Samedi),
            'sunday'    => $this->formatOpening($point->Horaires_Dimanche)
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
        $request = $this->requestStack->getMainRequest();
        if (null === $shippingAddress) {
            return [];
        }

        $options = [
            'Pays'           => $shippingAddress->getCountryCode(),
            'Ville'          => $shippingAddress->getCity(),
            'CP'             => $request->get('postCode', $shippingAddress->getPostcode()),
            'Action'         => '24R',
            'DelaiEnvoi'     => '0',
            'RayonRecherche' => '50',
        ];

        $points = $this->soapClient->findPickupPoints($options);

        foreach ($points as $point) {
            $pickupPoints[] = $this->transform($point);
        }

        return $pickupPoints;
    }

    /**
     * Format opening day
     *
     * @param stdClass $opening
     * @return string
     */
    public function formatOpening(stdClass $opening): string
    {
        if (!$opening) return '';

        $date = '';
        foreach ($opening as $hour) {
            if (intval($hour[0]) && intval($hour[1])) {
                $date .= $this->formatHour($hour[0]) . '-' . $this->formatHour($hour[1]);
            }

            if (intval($hour[2]) && intval($hour[3])) {
                $date .= ($date ? ' / ' : '') . $this->formatHour($hour[2]) . '-' . $this->formatHour($hour[3]);
            }
        }

        return $date;
    }

    /**
     * Format hour
     *
     * @param string $hour
     * @return string
     */
    public function formatHour(string $hour): string
    {
        return substr($hour, 0, 2) . 'h' . substr($hour, 2, 2);
    }

    /**
     * @param PickupPointCode $code
     *
     * @return PickupPointInterface|null
     */
    public function findPickupPoint(PickupPointCode $code): ?PickupPointInterface
    {
        $point = $this->soapClient->findPickupPoint(['Num' => $code->getIdPart(), 'Pays' => $code->getCountryPart()]);

        if ($point) {
            return $this->transform($point);
        }

        return null;
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
        return 'mondial_relay';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Mondial Relay';
    }
}
