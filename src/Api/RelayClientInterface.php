<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api;

/**
 * Interface RelayClientInterface
 * @package Asdoria\SyliusPickupPointPlugin\Api
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface RelayClientInterface
{
    public function findPickupPoints(array $params): array;
    public function findPickupPoint(array $params): ?\stdClass;
}
