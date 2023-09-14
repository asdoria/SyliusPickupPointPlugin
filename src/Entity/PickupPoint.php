<?php

declare(strict_types=1);


namespace Asdoria\SyliusPickupPointPlugin\Entity;

use Asdoria\SyliusPickupPointPlugin\Model\Aware\OpeningAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPoint as SetonoPickupPoint;

/**
 * Class PickupPoint
 * @package Asdoria\SyliusPickupPointPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
class PickupPoint extends SetonoPickupPoint implements OpeningAwareInterface
{

    protected array $opening = [];

    /**
     * @return array
     */
    public function getOpening(): array
    {
        return $this->opening;
    }

    /**
     * @param array $opening
     */
    public function setOpening(array $opening): void
    {
        $this->opening = $opening;
    }
}
