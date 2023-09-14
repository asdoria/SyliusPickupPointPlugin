<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\EventListener;

use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\PickupPointInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointProviderAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface as SetonoPickupPointShipmentInterface;
use Setono\SyliusPickupPointPlugin\Provider\ProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class PickupPointListener
 * @package Asdoria\SyliusPickupPointPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
abstract class PickupPointListener
{

    /**
     * @param ServiceRegistryInterface $providerRegistry
     */
    public function __construct(
        protected DataTransformerInterface $pickupPointToIdentifierTransformer
    ) {

    }

    /**
     * @param OrderInterface $order
     *
     * @return PickupPointInterface|null
     */
    public function getPickupPoint(OrderInterface $order): ?PickupPointInterface
    {
        $shipment = $order->getShipments()->current();

        if (!$shipment instanceof SetonoPickupPointShipmentInterface) return null;
        if (empty($shipment->getPickupPointId())) return null;

        if (! $shipment->getMethod()   instanceof PickupPointProviderAwareInterface) return null;
        if (empty($shipment->getMethod()->getPickupPointProvider())) return null;

        $pickupPoint = $this->pickupPointToIdentifierTransformer->reverseTransform($shipment->getPickupPointId());

        return $pickupPoint;
    }
}
