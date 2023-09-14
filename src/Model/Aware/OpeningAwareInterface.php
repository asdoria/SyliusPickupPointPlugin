<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Model\Aware;

/**
 * Class OpeningAwareInterface
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
interface OpeningAwareInterface
{
    /**
     * @return array
     */
    public function getOpening(): array;

    /**
     * @param array $opening
     */
    public function setOpening(array $opening): void;
}
