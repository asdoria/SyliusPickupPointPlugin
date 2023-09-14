<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\Order;

final class OrderInitializeCompleteListener extends PickupPointListener
{
    /**
     * Update Shipping Address on cart summary
     *
     * @param ResourceControllerEvent $event
     * @return void
     */
    public function updateShippingAddress(ResourceControllerEvent $event): void
    {
        /** @var Order $order */
        $order = $event->getSubject();

        $pickup = $this->getPickupPoint($order);

        if (empty($pickup)) return;

        $shipping = clone $order->getShippingAddress();
        $shipping->setCompany($pickup->getName());
        $shipping->setStreet($pickup->getAddress());
        $shipping->setCity($pickup->getCity());
        $shipping->setPostcode($pickup->getZipCode());
        $shipping->setCountryCode($pickup->getCountry());

        $order->setShippingAddress($shipping);
    }
}
