<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Saver;

use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface as SetonoPickupPointShipmentInterface;
use Setono\SyliusPickupPointPlugin\Model\ShippingMethodInterface as PickupPointShippingMethodInterface;
use Sylius\Component\Core\Customer\OrderAddressesSaverInterface;
use Sylius\Component\Core\Customer\CustomerAddressAdderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;

class CustomerOrderAddressesSaver implements OrderAddressesSaverInterface
{

    /**
     * @param CustomerAddressAdderInterface $addressAdder
     */
    public function __construct(protected CustomerAddressAdderInterface $addressAdder)
    {
    }


    /**
     * @param OrderInterface $order
     */
    public function saveAddresses(OrderInterface $order): void
    {
        $this->processPickupPoint($order);

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        if (null === $customer->getUser()) return;

        $this->addAddress($customer, $order->getBillingAddress());

        $currentShipment = $order->getShipments()->current();
        if (!$currentShipment instanceof SetonoPickupPointShipmentInterface) return;
        if (!empty($currentShipment->getPickupPointId())) return;

        $this->addAddress($customer, $order->getShippingAddress());
    }

    /**
     * @param OrderInterface $order
     *
     * @return void
     */
    protected function processPickupPoint(OrderInterface $order): void {
        $currentShipment = $order->getShipments()->current();

        if (!$currentShipment instanceof SetonoPickupPointShipmentInterface) return;
        if (empty($currentShipment->getPickupPointId())) return;

        $method = $currentShipment->getMethod();
        if (!$method instanceof PickupPointShippingMethodInterface) return;
        if (empty($method->getPickupPointProvider())) return;
        
        $pickupPointId = PickupPointCode::createFromString($currentShipment->getPickupPointId());
        $order->getShippingAddress()
            ->setCompany(sprintf('%s (%s)',$order->getShippingAddress()->getCompany(), $pickupPointId->getIdPart()));
    }

    /**
     * @param CustomerInterface $customer
     * @param AddressInterface|null $address
     */
    private function addAddress(CustomerInterface $customer, ?AddressInterface $address): void
    {
        if (null !== $address) {
            $this->addressAdder->add($customer, clone $address);
        }
    }
}
