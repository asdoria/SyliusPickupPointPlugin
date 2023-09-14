<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\EventListener;

use Sylius\Component\Core\Factory\AddressFactoryInterface;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Sylius\Component\Core\Repository\ShipmentRepositoryInterface;
use Sylius\Component\Core\Factory\AddressFactory;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Core\Model\Order;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class OrderPreCompleteListener
 * @package Asdoria\SyliusPickupPointPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class OrderPreCompleteListener extends PickupPointListener
{

    /**
     * @var AddressFactoryInterface|AddressFactory
     */
    protected AddressFactoryInterface $addressFactory;

    /**
     * @param AddressFactoryInterface $addressFactory
     */
    public function setAddressFactory(AddressFactoryInterface $addressFactory): void
    {
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param GenericEvent $event
     */
    public function addShippingAddress(GenericEvent $event): void
    {
        /** @var Order $order */
        $order = $event->getSubject();

        $pickup = $this->getPickupPoint($order);

        if (empty($pickup)) return;

        $shipping = $order->getShippingAddress();

        $address = $this->addressFactory->createNew();
        $address->setFirstName($shipping->getFirstName());
        $address->setLastName($shipping->getLastName());
        $address->setPhoneNumber($shipping->getPhoneNumber());
        $address->setCompany($pickup->getName());
        $address->setStreet($pickup->getAddress());
        $address->setCity($pickup->getCity());
        $address->setPostcode($pickup->getZipCode());
        $address->setCountryCode($pickup->getCountry());

        $order->setShippingAddress($address);
    }
}
